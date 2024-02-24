<?php
if (isset($_GET['c'])) {
    $class = $_GET['c'];
} else {
    $class = null;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>E-School - Skybyn</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <style>
        </style>
    </head>
    <body>
        <div class="header">
            <div class="logo">
                <img src="skybyn_school.png">
            </div>
        </div>

        <?php if ($class == null) {?>
        <div class="grid classes">
            <div class="class" onclick="window.location.href='?c=technology'">
                <div class="class_image"><img src="technology.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Technology<br><span>&</span><br>Computer Science</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="language.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Language</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="math.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Mathematics</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="art.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Art<br><span>&</span><br>Creativity</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="history.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>History<br><span>&</span><br>Social Sciences</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="health.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Health<br><span>&</span><br>Wellness</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="business.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Business<br><span>&</span><br>Entrepreneurship</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="cooking.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Cooking<br><span>&</span><br>Nutrition</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="music.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Music<br><span>&</span><br>Entertainment</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="sports.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Sports</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="personal_development.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Personal Development<br><span>&</span><br>Self-Improvement</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="travel.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Travel<br><span>&</span><br>Cultures</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="hobbies.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Hobbies<br><span>&</span><br>Interests</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="environment.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Environment<br><span>&</span><br>Sustainability</p></div>
                </div>
            </div>
            <div class="class">
                <div class="class_image"><img src="philosophy.jpg"></div>
                <div class="class_info">
                    <div class="class_name"><p>Philosophy<br><span>&</span><br>Wisdom</p></div>
                </div>
            </div>
        </div>
        <?php } else
        if ($class == "technology") {?>
        <?php } else
        if ($class == "language") {?>
        <?php } else
        if ($class == "math") {?>
        <?php } else
        if ($class == "art") {?>
        <?php } else
        if ($class == "history") {?>
        <?php } else
        if ($class == "health") {?>
        <?php } else
        if ($class == "business") {?>
        <?php } else
        if ($class == "cooking") {?>
        <?php } else
        if ($class == "music") {?>
        <?php } else
        if ($class == "sports") {?>
        <?php } else
        if ($class == "personal_development") {?>
        <?php } else
        if ($class == "travel") {?>
        <?php } else
        if ($class == "hobbies") {?>
        <?php } else
        if ($class == "environment") {?>
        <?php } else
        if ($class == "philosophy") {?>
        <?php }?>
    </body>
</html>