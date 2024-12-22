<?php

require_once 'assets/db.php';

header("Content-Type: application/json");

function sendResponse($status, $message, $data = null) {
    $response = [
        'status' => $status,
        'message' => $message
    ];
    if ($data) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit;
}

if (isset($_POST['register'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $boat = rand(100000, 999999);
    $created = time();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse('error', 'Invalid email format');
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        sendResponse('error', 'Passwords do not match');
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        sendResponse('error', 'Email already exists');
    }

    // Register new user
    $hashed_password = hash("sha512", $password);
    $stmt = $conn->prepare("INSERT INTO users (email, password, boat, created) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $email, $hashed_password, $boat, $created);
    
    if ($stmt->execute()) {
        sendResponse('success', 'Registration successful!', [
            'id' => $stmt->insert_id,
            'boat' => $boat
        ]);
    } else {
        sendResponse('error', 'Registration failed');
    }
    $stmt->close();
}

if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;

    if (isset($_POST['method'])) {
        $method = $_POST['method'];
        $stmt = $conn->prepare("SELECT id, boat, email, phone FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            sendResponse('success', 'Login successful!', [
                'id' => $user['id'],
                'boat' => $user['boat'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ]);
        } else {
            sendResponse('error', 'Email not found. Please register.');
        }
        $stmt->close();
    } else {
        $hashed_password = hash("sha512", $password);
        $stmt = $conn->prepare("SELECT id, boat, email, phone FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            sendResponse('success', 'Login successful!', [
                'id' => $user['id'],
                'boat' => $user['boat'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ]);
        } else {
            sendResponse('error', 'Invalid email or password.');
        }
        $stmt->close();
    }
}

if (isset($_POST['coords'])) {
    $userID = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);
    $timestamp = time();

    $stmt = $conn->prepare("INSERT INTO coords (user, latitude, longitude, timestamp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddi", $userID, $latitude, $longitude, $timestamp);
    
    if ($stmt->execute()) {
        sendResponse('success', 'Location updated');
    } else {
        sendResponse('error', 'Failed to update location: ' . $stmt->error);
    }
    $stmt->close();
}

if (isset($_POST['userID'])) {
    $userID = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    if ($count === 1) {
        sendResponse('success', 'User verified');
    } else {
        sendResponse('error', 'User not found');
    }
    $stmt->close();
}

if (isset($_POST['update'])) {
    $userId = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
    $boat = $_POST['boat'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET boat = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $boat, $phone, $userId);
    
    if ($stmt->execute()) {
        sendResponse('success', 'User details updated successfully');
    } else {
        sendResponse('error', 'Failed to update user details: ' . $stmt->error);
    }
    $stmt->close();
}

if (isset($_POST['sos'])) {
    $userId = $_POST['userID'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $boat = $_POST['boat'];
    
    // Store SOS signal in database
    $query = "INSERT INTO sos_signals (id, latitude, longitude, boat_name, timestamp) 
              VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idds", $userId, $latitude, $longitude, $boat);
    
    if ($stmt->execute()) {
        // Send notifications to nearby users
        $notifyQuery = "SELECT id, email, boat, 
                              ST_Distance_Sphere(
                                  point(longitude, latitude), 
                                  point(?, ?)
                              ) as distance 
                       FROM users 
                       WHERE ST_Distance_Sphere(
                           point(longitude, latitude), 
                           point(?, ?)
                       ) <= 50000 
                       AND id != ?
                       ORDER BY distance"; // 50km radius, ordered by distance
        $notifyStmt = $conn->prepare($notifyQuery);
        $notifyStmt->bind_param("ddddi", $longitude, $latitude, $longitude, $latitude, $userId);
        $notifyStmt->execute();
        $result = $notifyStmt->get_result();
        
        while ($user = $result->fetch_assoc()) {
            // Send email notification
            $to = $user['email'];
            $subject = "⚠️ SOS EMERGENCY ALERT ⚠️";
            $message = "Emergency SOS signal received from boat: $boat\n";
            $message .= "Location: $latitude, $longitude\n";
            $message .= "Distance: " . round($user['distance']/1000, 2) . " km away\n";
            $message .= "Please contact coast guard if you are nearby.";
            
            mail($to, $subject, $message);
        }
        
        echo json_encode([
            "status" => "success", 
            "message" => "SOS signal sent successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to send SOS signal"
        ]);
    }
    exit;
}

// Update user location
if (isset($_POST['update_location'])) {
    $userId = $_POST['id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    
    $query = "UPDATE users SET last_latitude = ?, last_longitude = ?, last_active = NOW() 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ddi", $latitude, $longitude, $userId);
    
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Location updated"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update location"
        ]);
    }
    exit;
}

// Add this new endpoint for getting user data
if (isset($_POST['get_user'])) {
    $userId = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
    
    $stmt = $conn->prepare("SELECT id, email, phone, boat FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        sendResponse('success', 'User data retrieved', [
            'id' => $user['id'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'boat' => $user['boat']
        ]);
    } else {
        sendResponse('error', 'User not found');
    }
    $stmt->close();
}

// Get active users
if (isset($_POST['get_users'])) {
    $userId = $_POST['id'];
    
    // Get users who updated their location in the last 5 minutes
    $query = "SELECT u.id, u.boat, u.last_latitude, u.last_longitude, last_active 
              FROM users u 
              WHERE last_active >= NOW() - INTERVAL 5 MINUTE 
              AND id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($user = $result->fetch_assoc()) {
        $users[] = [
            'id' => $user['id'],
            'boat' => $user['boat'],  // Make sure boat name is included
            'latitude' => $user['latitude'],
            'longitude' => $user['longitude'],
            'last_update' => $user['last_update']
        ];
    }
    
    echo json_encode($users);
    exit;
}

$conn->close();

?>