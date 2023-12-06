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
    $ra = "DELETE FROM `page_members`
        WHERE `user`='$user'
        AND `page`='$page'
    ";
    mysqli_query($conn, $ra);
    
    #$pq = "DELETE FROM `posts`
    #    WHERE `id`='$pid'";
    #mysqli_query($conn, $pq);
    #$cq = "DELETE FROM `comments`
    #    WHERE `post_id`='$pid'";
    #mysqli_query($conn, $cq);
    #$fq = "DELETE FROM `page_uploads`
    #    WHERE `post`='$pid'";
    #mysqli_query($conn, $fq);
    #$ffq = "SELECT *
    #    FROM `page_uploads`
    #    WHERE `post`='$pid'";
    #$ffr = mysqli_query($conn, $ffq);
    #$ffcount = mysqli_num_row($ffr);
    #if ($ffcount > 0) {
    #    $file = mysqli_fetch_assoc($ffr);
    #    unlink($file['file']);
    #}

    #$json = array(
    #    "responseCode"=>"1",
    #    "message"=>"Post deleted"
    #);
    #echo json_encode($json);
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"You left the page"
    );

    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"You have already left this page"
    );

    echo json_encode($json);
}
?>