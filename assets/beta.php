<?php include_once('./conn.php');

$password = 'Koloni010';

#if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== $password) {
#    header('WWW-Authenticate: Basic realm="Beta Access"');
#    header('HTTP/1.0 401 Unauthorized');
#    $result = json_encode(array('error' => 'Unauthorized'));
#    exit;
#}

if (isset($_GET['user'])) {
    $user = $_GET['user'];
    $result = $conn->query("SELECT * FROM `beta_access` WHERE `user_id` = '$user'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = array(
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'created_at' => $row['created_at']
        );

        $result = json_encode($user);
    } else {
        $result = json_encode(array('error' => 'User is not a beta tester'));
    }
} else 
if (isset($_GET['generate'])) { // Generate beta access key(s)
    $amount = $_GET['generate'];

    if ($amount > 10) {
        $result = json_encode(array('error' => 'You can only generate a maximum of 10 keys at a time'));
        exit();
    } else
    if ($amount < 1) {
        $amount = 1;
    }

    for ($i = 0; $i < $amount; $i++) { // Generate key(s) containing 10 characters of random letters and numbers
        $key = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 10)), 0, 10);
        $conn->query("INSERT INTO `beta_access` (`key`) VALUES ('$key')");
    }

    $result = json_encode(array('success' => 'Generated ' . $amount . ' key(s)'));
} else
if (isset($_GET['list'])) { // List all beta testers
    $result = $conn->query("SELECT * FROM `beta_access`");
    $users = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email'],
                'created_at' => $row['created_at']
            );

            array_push($users, $user);
        }

        $result = json_encode($users);
    } else {
        $result = json_encode(array('error' => 'No beta testers found'));
    }
} else
if (isset($_GET['keys'])) { // List all beta access keys
    $result = $conn->query("SELECT * FROM `beta_access` WHERE `user_id` IS NULL");
    if ($result->num_rows > 0) {
        $keys = array();

        while ($row = $result->fetch_assoc()) {
            array_push($keys, $row['key']);
        }

        $result = json_encode($keys);
    } else {
        $result = json_encode(array('error' => 'No beta access keys found'));
    }
} else {
    $result = json_encode(array('error' => 'No data provided'));
}
?>