<?php require_once "./functions.php";

$group = $_POST['group'];
$last = $_POST['last'];
$length = strlen("chat_$group"."_");
$last = substr($last,$length);

$getMessages = $conn->query("SELECT * FROM `group_messages` WHERE `group`='$group' ORDER BY `date` DESC");
while($message = mysqli_fetch_assoc($getMessages)) {
    $message_id = $message['id'];
    $message_user = $message['user'];
    $message_content = $message['content'];
    $message_created = date("Y-m-d H:i:s", $message['date']);
    $message_system = $message['system'];
    
    $getUserData = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$message_user'");
    $gUser = mysqli_fetch_assoc($getUserData);
    $guser_id = $gUser['id'];
    $guser_name = $gUser['username'];
    $guser_avatar = $gUser['avatar'];

    $msgID = "chat_".$group."_".$message_id;

    if ($message_id > $last) {
        if ($guser_avatar == "") {
            $guser_avatar = "./assets/images/logo_faded_clean.png";
        }

        if ($message_id > $last) {
            if ($guser_id == $uid) {?>
                    <div class="gchat-message me" id="<?=$msgID?>">
                        <div class="gchat-message-options">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="gchat-message-option-list">
                                <div class="gchat-action" onclick="editMsg(<?=$message_id?>)">
                                    <i class="fa-regular fa-pen-to-square"></i>Edit
                                </div>
                                <div class="gchat-action" onclick="delMsg(<?=$message_id?>)">
                                    <i class="fa-solid fa-trash"></i>Delete
                                </div>
                            </div>
                        </div>
                        <div class="gchat-message-box">
                            <div class="gchat-text"><?=$message_content?></div>
                        </div>
                        <div class="gchat-user">
                            <img src="<?=$guser_avatar?>">
                        </div>
                    </div>
            <?php } else {?>
                    <div class="gchat-message" id="<?=$msgID?>">
                        <div class="gchat-user">
                            <img src="<?=$guser_avatar?>">
                        </div>
                        <div class="gchat-text"><?=$message_content?></div>
                    </div>
            <?php }
        }
    }
}
?>