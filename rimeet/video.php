<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_SESSION['driver']) || isset($_SESSION['joiner'])) {
    if (isset($_SESSION['driver'])) {
        $id = $_SESSION['driver'];
    } else {
        $id = $_SESSION['joiner'];
    }
    $checkDriver = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$id'");
    if ($checkDriver->num_rows > 0) {
        if (!empty($driver)) {
            $plate = driver("id", $id, "default_car");
        } else {
            $plate = joiner("id", $id, "license_plate");
        }
        $checkJoiners = $conn->query("SELECT * FROM `joiners` WHERE `license_plate` = '$plate'");
        if ($checkJoiners->num_rows > 0) {
            $joinerData = $checkJoiners->fetch_assoc();
            $meet_id = $joinerData['meet_id'];
            $checkMeet = $conn->query("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
            if ($checkMeet->num_rows > 0) {
                $meet = true;
                $meetData = $checkMeet->fetch_assoc();
                $meetName = $meetData['name'];
                $meetTime = $meetData['time'];
            } else {
                $meet = false;
            }
        } else {
            $meet = false;
        }
    } else {
        $meet = false;
    }
} else {
    $meet = false;
} ?>
<style>
    #videoUpload {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 90%;
        height: calc(100vh - 60px);
        background: rgba(0, 0, 0, 0.5);
        border-radius: 20px;
        z-index: 100;
    }
</style>
<div class="videos">
    <?php if ($meet == true) {?>
    <h1><?=$meetName?></h1>
    <div class="video_gallery">
        <?php
        $getVideos = $conn->query("SELECT * FROM `videos` WHERE `meet_id` = '$meet_id'");
        while ($video = $getVideos->fetch_assoc()) {
            $v_link = $video['link'];
        ?>
        <div class="video">
            <video src="<?=$v_link?>" controls></video>
        </div>
        <?php }?>
    </div>
    <?php } else {
        $checkVideos = $conn->query("SELECT * FROM `videos`");
        if ($checkVideos->num_rows > 0) {
            $video = $checkVideos->fetch_assoc();
            $v_meet = $video['meet_id'];
            $v_link = $video['link'];?>
    <h1>Offentlige treff</h1>
    <div class="video_gallery">
        <?php
        $checkPublicMeets = $conn->query("SELECT * FROM `meets` WHERE `private` = 0");
        while ($publicMeet = $checkPublicMeets->fetch_assoc()) {
            $publicMeetId = $publicMeet['id'];
            $publicMeetName = $publicMeet['name'];
            $publicMeetTime = date("D d.M Y", $publicMeet['time']);
            $publicMeetLocation = $publicMeet['location'];

            $meet_id = $publicMeetId;

            if ($publicMeetId == $v_meet) {
            ?>
            <div class="video">
                <h2><?=$publicMeetName?></h2>
                <p><?=$publicMeetTime?></p>
                <p><?=$publicMeetLocation?></p>
                <video src="<?=$v_link?>" controls></video>
            </div>
            <?php 
            }
        }?>
    </div>
    <?php } else {?>
    <h1>Ingen videoer</h1>
    <?php }
    }?>
</div>

<?php if ($meet == true) {?>
<script>
    document.getElementById('uploadVideo').addEventListener('click', uploadVideo);
    function uploadVideo() {
        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'video/*';
        input.addEventListener('change', handleFileUpload);
        input.click();

        function handleFileUpload(event) {
            var file = event.target.files[0];
            // Handle the file upload logic here
            var formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'video');
            formData.append('meet_id', <?=$meet?>);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'assets/upload.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // File upload success
                    console.log('File uploaded successfully');
                } else {
                    // File upload error
                    console.log('File upload failed');
                }
            };
            xhr.send(formData);
        }
    }
</script>
<?php } else {?>
<script>
    document.getElementById('uploadVideo').addEventListener('click', uploadVideo);
    function uploadVideo() {
        alert("Du må være med i et treff for å laste opp videoer.");
    }
</script>
<?php }?>