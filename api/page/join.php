<?php include_once(__DIR__."/../../config.php");

$page = $_POST['pageID'];
$user = $_POST['userID'];

$mdq = "SELECT *
    FROM `page_members`
    WHERE `page`='$page'
    AND `user`='$user'";
$mdresult = mysqli_query($conn, $mdq);
$mdcount = mysqli_num_rows($mdresult);
if ($mdcount == 0) {
    $ra = "INSERT INTO `page_members`
        (
            `page`,
            `user`,
            `rank`,
            `status`
        )
        VALUES
        (
            '$page',
            '$user',
            '0',
            'member'
        )
    ";
    mysqli_query($conn, $ra);
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"You are now a member"
    );

    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"You are already a member of this page"
    );

    echo json_encode($json);
}
?>