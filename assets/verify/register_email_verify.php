<?php 
include "functions.php";

// Assuming $conn is your database connection from 'functions.php'

$code = $_POST['code'] ?? ''; // Null coalescing operator to handle the case where 'code' is not set

// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM `email_check` WHERE `code` = ?");
$stmt->bind_param("s", $code); // 's' specifies the variable type => 'string'
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Prepare the update statement
    $updateStmt = $conn->prepare("UPDATE `email_check` SET `verified` = 1 WHERE `code` = ?");
    $updateStmt->bind_param("s", $code);
    $updateStmt->execute();

    // Check for errors in update operation
    if ($updateStmt->error) {
        echo "Error: " . $updateStmt->error;
    } else {
        echo "ok";
    }

    $updateStmt->close();
} else {
    echo "Code not found or multiple entries found.";
}

$stmt->close();
?>
