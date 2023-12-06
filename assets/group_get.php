<?php require_once "./functions.php";

$group = $_POST['group'];
$current = $_POST['current'];

$conn->query("SELECT * FROM `group_messages` WHERE `group`='$group'");

$getMessages = mysqli_query($conn, "SELECT * FROM `group_messages` WHERE `group`='$group' ORDER BY `date` ASC");
if ($getMessages->num_rows > $current) {
    $message = mysqli_fetch_assoc($getMessages);
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
    
    if ($guser_avatar == "") {
        $guser_avatar = "./assets/images/logo_faded_clean.png";
    }
    
    if ($guser_id == $uid) {?>
                <div class="chat_message me" id="chat_<?=$message_id?>">
                    <div class="chat_message_options">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        <div class="chat_message_option_list">
                            <div class="chat_action">
                                <i class="fa-solid fa-share"></i>Share
                            </div>
                            <div class="chat_action">
                                <i class="fa-solid fa-trash"></i>Delete
                            </div>
                        </div>
                    </div>
                    <div class="chat_message_box">
                        <div class="chat_text"><?=$message_content?></div>
                    </div>
                    <div class="chat_user">
                        <img src="<?=$guser_avatar?>">
                    </div>
                </div>
    <?php } else {?>
                <div class="chat_message" id="chat_<?=$message_id?>">
                    <div class="chat_user">
                        <img src="<?=$guser_avatar?>">
                    </div>
                    <div class="chat_text"><?=$message_content?></div>
                </div>
    <?php 
    }
}
?>