<?php include "functions.php";

$friend_id = $_POST['friend_id'];


$checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend_id'");
if ($checkFriendship->num_rows == 1) {
    $friendshipData = $checkFriendship->fetch_assoc();
    $status = $friendshipData['status'];

    if ($status == "friends") {?>
    <button onclick="friendship('<?= $user_id ?>','unfriend')">
        <i class="fa-solid fa-user-minus"></i> Unfriend
    </button>
<?php } else if ($status == "sent") {?>
    <button onclick="friendship('<?= $user_id ?>','cancel')">
        <i class="fa-solid fa-user-xmark"></i> Cancel friend request
    </button>
<?php } else if ($status == "received") {?>
    <button onclick="friendship('<?= $user_id ?>','accept')">
        <i class="fa-solid fa-user-check"></i> Accept
    </button>
    <button onclick="friendship('<?= $user_id ?>','ignore')">
        <i class="fa-solid fa-user-xmark"></i> Ignore
    </button>
<?php } else if ($status == "blocked") {?>
    <button onclick="friendship('<?= $user_id ?>','unblock')">
        <i class="fa-solid fa-user-slash"></i> Unblock
    </button>
<?php }
} else {?>
<button onclick="friendship('<?= $user_id ?>','send')">
    <i class="fa-solid fa-user-plus"></i> Send friend request
</button>
<button onclick="friendship('<?= $user_id ?>','block')">
    <i class="fa-solid fa-user-slash"></i> Block
</button>
<?php }?>
<button onclick="friendship('<?= $user_id ?>','report')">
<i class="fa-solid fa-triangle-exclamation"></i> Report
</button>