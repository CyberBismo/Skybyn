<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    //?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

if (isset($_GET['new'])) {
    
}
?>
        <div class="page-container">
            <div class="event-head">
                Events near you
            </div>
            <div class="event-browse">
                <?php $getEvents = $conn->query("SELECT * FROM `events` WHERE `private`='0'");
                if ($getEvents->num_rows > 0) {
                    while($event = $getEvents->fetch_assoc()){
                        $e_id = $event['id'];
                        $e_name = $event['name'];
                        $e_desc = $event['desc'];
                        $e_icon = $event['icon'];
                        $e_banner = $event['banner'];
                        $e_owner = $event['owner'];
                        ?>
                        <?php
                    }
                } else {
                    ?>
                    <div class="event-intro" onclick="window.location.href='/event?new'">Create an event</div>
                    <?php
                }
                ?>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>