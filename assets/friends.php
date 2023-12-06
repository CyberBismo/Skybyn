<div class="friend_list_back" id="friend-list" onclick="hideFriends(event, this)" hidden>
    <div class="friend_list">
        <?php if (isset($_SESSION['user'])) {
        $getFriends = mysqli_query($conn, "SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `status`='friends'");
        while($friend = mysqli_fetch_assoc($getFriends)) {
            $friend_id = $friend['friend_id'];
            
            $getFriendData = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$friend_id'");
            $friendData = mysqli_fetch_assoc($getFriendData);
            $friend_name = $friendData['username'];
            $friend_avatar = $friendData['avatar'];
            ?>
        <div class="friend">
            <div class="avatar">
                <img src="<?=$friend_avatar?>">
            </div>
            <div class="name">
                <?=$friend_name?>
            </div>
            <div class="actions">
                <i class="fa-regular fa-comment-dots" onclick="window.location.href='./chat.php?id=<?=$friend_id?>'"></i>
                <i class="fa-solid fa-image-portrait"></i>
                <i class="fa-regular fa-star"></i>
            </div>
        </div>
        <?php }} else {?>
        <div class="friend">
            You must be logged in to view the list of friends.
        </div>
        <?php }?>
    </div>
</div>