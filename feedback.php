<?php include_once "./assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
    return;
}

$fba = false;

if (isset($rank) && $rank > 5) {
    $fba = true;
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
                                <?php if ($fba == true) {?>
                                <div class="feedback-action" onclick="deleteFeedback(<?=$feedback_id;?>)">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </div>
                                <?php }?>

                                <?php if ($feedback_solved == 1) {?>
                                <div class="feedback-action" onclick="solveFeedback(<?=$feedback_id;?>)">
                                    Solved <i id="solved_<?=$feedback_id;?>" class="fa-solid fa-circle-check"></i>
                                </div>
                                <?php } else {?>
                                <div class="feedback-action" onclick="solveFeedback(<?=$feedback_id;?>)">
                                    <i id="unsolved_<?=$feedback_id;?>" class="fa-regular fa-circle"></i>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
        
        <?php if ($fba == true) {?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var betaFeedbackDiv = document.getElementById("beta-feedback");
                if (betaFeedbackDiv) {
                    var shortcutsDiv = document.createElement("div");
                    shortcutsDiv.className = "shortcuts beta-keys";
                    shortcutsDiv.innerHTML = `
                        <h3><i class="fa-solid fa-key"></i><div>BETA Keys</div><i></i></h3>
                        <div id="beta-keys">
                            <?php
                            $getBetaKeys = $conn->query("SELECT * FROM `beta_access`");
                            if ($getBetaKeys->num_rows > 0) {
                                while($keyData = $getBetaKeys->fetch_assoc()) {
                                    $key = $keyData['key'];
                                    $key_assigned = $keyData['user_id'];

                                    if (empty($key_assigned)) {
                                        ?>
                                        <div class="sortcut beta-key">
                                            <p><?=$key?></p>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    `;
                    betaFeedbackDiv.insertAdjacentElement('afterend', shortcutsDiv);
                }
            });
        </script>
        <?php }?>
    </body>
</html>