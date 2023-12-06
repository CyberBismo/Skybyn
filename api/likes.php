<?php include_once('../db_conn.php');
$pid = $_POST['postID'];
$cid = $_POST['commentID'];
$uid = $_POST['userID'];
if (!empty($uid)) {
    if (!empty($pid)) {
        $q = "SELECT *
            FROM `likes`
            WHERE `pid`='$pid'
            AND `uid`='$uid'";
        $likes = mysqli_query($conn, $q);
        $count = mysqli_num_rows($likes);
        if ($count == 1) {
            $json = array(
                "responseCode"=>"1",
                "message"=>"You like this post"
            );
            echo json_encode($json);
        } else {
            $json = array("responseCode"=>"0","message"=>"You don't like this post");
            echo json_encode($json);
        }
    } else {
        $q = "SELECT *
            FROM `likes`
            WHERE `cid`='$cid'
            AND `uid`='$uid'";
        $likes = mysqli_query($conn, $q);
        $count = mysqli_num_rows($likes);
        if ($count == 1) {
            $json = array(
                "responseCode"=>"1",
                "message"=>"You like this comment"
            );
            echo json_encode($json);
        } else {
            $json = array("responseCode"=>"0","message"=>"You don't like this comment");
            echo json_encode($json);
        }
    }
} else {
    if (!empty($pid)) {
        $q = "SELECT *
            FROM `likes`
            WHERE `pid`='$pid'";
        $likes = mysqli_query($conn, $q);
        $count = mysqli_num_rows($likes);
        $json = array(
            "responseCode"=>"1",
            "posts"=>"1",
            "likes"=>"$count"
        );
        echo json_encode($json);
    } else {
        $q = "SELECT *
            FROM `likes`
            WHERE `cid`='$cid'";
        $likes = mysqli_query($conn, $q);
        $count = mysqli_num_rows($likes);
        $json = array(
            "responseCode"=>"1",
            "comments"=>"1",
            "likes"=>"$count"
        );
        echo json_encode($json);
    }
}