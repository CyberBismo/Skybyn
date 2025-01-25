<?php include_once "./assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><script>window.location.href = "../";</script><?php
}

$admin = false;

if (isset($rank) && $rank > 5) {
    $admin = true;
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
                        $feedback_page = $feedback['url'];
                        $feedback_solved = $feedback['solved'];
                        $feedback_userData = $conn->query("SELECT * FROM `users` WHERE `id`='$feedback_user'");
                        $feedback_userData = $feedback_userData->fetch_assoc();
                        $feedback_username = $feedback_userData['username'];
                        $feedback_userAvater = "../".$feedback_userData['avatar'];

                        if ($feedback_userAvater == "../") {
                            $feedback_userAvater = "../assets/images/logo_faded_clean.png";
                        }

                        $feedback_date = date("d.m.Y H:i", $feedback_date);
                        ?>
                        <div class="feedback" id="feedback-<?=$feedback_id;?>">
                            <div class="feedback-header">
                                <div class="feedback-user">
                                    <img src="<?=$feedback_userAvater;?>" alt="User Avatar">
                                    <p><?=$feedback_username;?></p>
                                </div>
                                <div class="feedback-date">
                                    <p><?=$feedback_date;?></p>
                                </div>
                            </div>
                            <div class="feedback-content">
                                <p><?=$feedback_content;?></p>
                                <div class="feedback-page">
                                    <p><?=$feedback_page;?></p><button onclick="window.open('<?=$feedback_page;?>', '_blank')">Go to</button>
                                </div>
                            </div>
                            <div class="feedback-actions">
                                <?php if ($admin == true) {?>
                                <div class="feedback-action" onclick="deleteFeedback(<?=$feedback_id;?>)">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </div>
                                <?php }?>

                                <?php if ($feedback_solved == 1) {?>
                                <div class="feedback-action" onclick="solveFeedback(<?=$feedback_id;?>)">
                                    Solved <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <?php } else {?>
                                <div class="feedback-action" onclick="solveFeedback(<?=$feedback_id;?>)">
                                    <i id="unsolved" class="fa-regular fa-circle"></i>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </body>
</html>