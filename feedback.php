<?php include_once "./assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><script>window.location.href = "../";</script><?php
}
?>
        <div class="page-container">
            <div class="feedback-admin">
                <h3>Feedback</h3>
                <div id="feedback">
                    <?php
                    $getFeedback = $conn->query("SELECT * FROM `feedback` ORDER BY `id` DESC");
                    while ($feedback = $getFeedback->fetch_assoc()) {
                        $feedback_id = $feedback['id'];
                        $feedback_user = $feedback['user'];
                        $feedback_date = $feedback['date'];
                        $feedback_content = $feedback['content'];
                        $feedback_userData = $conn->query("SELECT `username` FROM `users` WHERE `id`='$feedback_user'");
                        $feedback_userData = $feedback_userData->fetch_assoc();
                        $feedback_username = $feedback_userData['username'];
                        ?>
                        <div class="feedback">
                            <div class="feedback-header">
                                <div class="feedback-user">
                                    <img src="assets/images/user.png">
                                    <p><?=$feedback_username;?></p>
                                </div>
                                <div class="feedback-date">
                                    <p><?=$feedback_date;?></p>
                                </div>
                            </div>
                            <div class="feedback-content">
                                <p><?=$feedback_content;?></p>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </body>
</html>