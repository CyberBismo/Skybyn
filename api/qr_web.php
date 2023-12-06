<?php include_once("../db_conn.php");

$code = $_POST['code'];

if (isset($_POST['delete'])) {
    $delete = $_POST['delete'];
    unlink("../qr/$delete.png");
    $delcode = "DELETE FROM `qr_codes`
        WHERE `code`='$delete'";
    $delete = mysqli_query($conn, $delcode);
}

$codepart = explode("_", $code);
$source = $codepart[0];

$checkCode = "SELECT *
    FROM `qr_codes`
    WHERE `code`='$code'
";
$ccres = mysqli_query($conn, $checkCode);
$ccc = mysqli_num_rows($ccres);
if ($ccc > 0) {
    if ($source == "web") {
        $ccdata = mysqli_fetch_assoc($ccres);
        $user = $ccdata['login'];
        $other = $ccdata['other'];
        
        if ($user != "0") {
            echo $user;
        } else {
            echo "false";
        }
    }
    if ($source == "app") {
        $ccdata = mysqli_fetch_assoc($ccres);
        $user = $ccdata['login'];
        $other = $ccdata['other'];
        $used = $ccdata['used'];
        
        if ($used == "1") {
            echo "used";
        } else {
            echo "false";
        }
    }
} else {
    echo "Invalid or expired QR code!";
}
?>