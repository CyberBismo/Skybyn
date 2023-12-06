<?php include_once('../db_conn.php');
$pid = $_POST['postID'];
$cid = $_POST['commentID'];
$uid = $_POST['userID'];

if (!empty($pid)) {
    $qpl = "SELECT *
        FROM `likes`
        WHERE `pid`='$pid'
        AND `uid`='$uid'";
    $check = mysqli_query($conn, $qpl);
    $countc = mysqli_num_rows($check);
    if ($countc == 0) {
        $q = "INSERT INTO `likes` (
                `pid`,
                `uid`
            )
            VALUES (
                '$pid',
                '$uid'
            )";
        $post = mysqli_query($conn, $q);
        if ($post) {
            $ql = "SELECT *
                FROM `likes`
                WHERE `pid`='$pid'";
            $lcheck = mysqli_query($conn, $ql);
            $likes = mysqli_num_rows($lcheck);
            $json = array(
                "responseCode"=>"1",
                "message"=>"Liked!",
                "likes"=>"$likes"
            );
            echo json_encode($json);
        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"Something went wrong liking that post."
            );
            echo json_encode($json);
        }
    } else {
        $q = "DELETE FROM `likes`
            WHERE `uid`='$uid'
            AND `pid`='$pid'";
        $post = mysqli_query($conn, $q);
        if ($post) {
            $ql = "SELECT *
                FROM `likes`
                WHERE `pid`='$pid'";
            $lcheck = mysqli_query($conn, $ql);
            $likes = mysqli_num_rows($lcheck);
            $json = array(
                "responseCode"=>"2",
                "message"=>"Disliked",
                "likes"=>"$likes"
            );
            echo json_encode($json);
        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"Something went wrong disliking that post."
            );
            echo json_encode($json);
        }
    }
} else {
    $qcl = "SELECT *
        FROM `likes`
        WHERE `cid`='$cid'
        AND `uid`='$uid'";
    $check = mysqli_query($conn, $qcl);
    $countc = mysqli_num_rows($check);
    if ($countc == 0) {
        $q = "INSERT INTO `likes` (
                `cid`,
                `uid`
            )
            VALUES (
                '$cid',
                '$uid'
            )";
        $comment = mysqli_query($conn, $q);
        if ($comment) {
            $ql = "SELECT *
                FROM `likes`
                WHERE `cid`='$cid'";
            $lcheck = mysqli_query($conn, $ql);
            $likes = mysqli_num_rows($lcheck);
            $json = array(
                "responseCode"=>"1",
                "message"=>"Liked!",
                "likes"=>"$likes"
            );
            echo json_encode($json);
        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"Something went wrong liking that comment."
            );
            echo json_encode($json);
        }
    } else {
        $q = "DELETE FROM `likes`
            WHERE `uid`='$uid'
            AND `cid`='$cid'";
        $comment = mysqli_query($conn, $q);
        if ($comment) {
            $ql = "SELECT *
                FROM `likes`
                WHERE `cid`='$cid'";
            $lcheck = mysqli_query($conn, $ql);
            $likes = mysqli_num_rows($lcheck);
            $json = array(
                "responseCode"=>"2",
                "message"=>"Disliked",
                "likes"=>"$likes"
            );
            echo json_encode($json);
        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"Something went wrong disliking that comment."
            );
            echo json_encode($json);
        }
    }
}