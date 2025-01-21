<?php include_once "functions.php";


$name = $_POST['group_name'];
$desc = $_POST['group_desc'];
$privacy = $_POST['group_privacy'];
$lock_type = $_POST['group_lock_type'];
$password = "";
$pin = "";
$rank = "6";

if ($privacy == "locked") {
    if ($lock_type == "password") {
        $password = $_POST['group_password'];
    }
    if ($lock_type == "pin") {
        $pin = $_POST['group_pin'];
    }
}

// Using prepared statements
$stmt = $conn->prepare("INSERT INTO `groups` (`name`, `description`, `owner`, `privacy`, `lock_type`, `password`, `pin`, `created`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssii", $name, $desc, $uid, $privacy, $lock_type, $password, $pin, $now);

if ($stmt->execute()) {
    $gid = $stmt->insert_id;
    $addMember = $conn->prepare("INSERT INTO `group_members` (`user`,`group`,`since`,`rank`) VALUES (?, ?, ?, ?)");
    $addMember->bind_param("iiii", $uid, $gid, $now, $rank);
    $addMember->execute();
    $response = [
        "response" => "ok",
        "message" => "$gid"
    ];

    echo json_encode($response);
} else {
    $response = [
        "response" => "error",
        "message" => "A problem occurred creating a new group"
    ];

    echo json_encode($response);
}
?>