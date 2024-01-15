<?php include_once "db.php";

error_reporting(0);

# Get full url
function domain() {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url = parse_url($url, PHP_URL_HOST);
    return $url;
}

# Get specified system_data
function skybyn($x) {
    global $conn;
    global $avatar;

    $systemData = $conn->query("SELECT * FROM `system_data` WHERE `data`='$x'");
    $SDRow = $systemData->fetch_assoc();

    if ($x == "logo" && isset($_SESSION['user'])) {
        return $avatar;
    } else {
        return $SDRow['text'];
    }
}

# Show errors
function showError() {
    $msg_display = ini_set('display_errors', 1);
    $msg_startup = ini_set('display_startup_errors', 1);
    $msg = error_reporting(E_ALL);
    return "$msg<br>$msg_startup<br>$msg_display";
}

# Get client IP
function getIP() {
    $ip = $_SERVER['REMOTE_ADDR'];

    // Check for additional headers if available
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }

    return $ip;
}

# Get geo location data from IP
function geoData($x) {
    $ip = getIP();
    // Send request to ipapi.com API
    $url = "https://freeipapi.com/api/json/$ip";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($x)) {
        #return $data[$x];
    } else {
        #return $response;
    }
    return "";
}

# Create cookie with country information
if (!isset($_COOKIE['country'])) {
    $country = geoData("country_name");
    createCookie("country",$country, "1","6");
}

# Get metadata from URL
function getMetadata($url) {
    // Get the HTML content from the URL
    $html = file_get_contents($url);
    
    if ($html === false) {
        return "Failed to fetch URL.";
    }

    // Parse the HTML and extract meta tags
    $metaTags = get_meta_tags($url);

    // Return the extracted meta tags as an associative array
    return $metaTags;
}

# Extract URL's from text
function extractUrls($text) {
    // Regular expression for URL extraction
    $urlPattern = '/\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/i';

    // Array to hold all extracted URLs
    $urls = array();

    // Perform the regular expression match
    if (preg_match_all($urlPattern, $text, $matches)) {
        // Add all matched URLs to the array
        $urls = $matches[0];
    }

    return $urls;
}

# Make simplified URL's clickable from the original URL
function simplifyAndMakeClickable($text) {
    // Regular expression to extract URLs
    $urlPattern = '/https?:\/\/[^\s]+/';

    // Find all URLs in the text
    if (preg_match_all($urlPattern, $text, $matches)) {
        foreach ($matches[0] as $url) {
            // Simplify each URL
            $simplifiedUrl = simplifyUrl($url);

            // Make each URL clickable
            $clickableUrl = '<a href="' . $url . '" target="_blank" title="' . $url . '">' . $simplifiedUrl . '</a>';

            // Replace the original URL in the text with its clickable, simplified version
            $text = str_replace($url, $clickableUrl, $text);
        }
    }

    return $text;
}

# Simplify URL
function simplifyUrl($url) {
    $pattern = "/https?:\/\/(www\.)?([^\/]*\.?)\/?.*/";
    $replacement = "$2";
    return preg_replace($pattern, $replacement, $url);
}

# Display video frame with youtube code when text contains youtube url
function convertVideo($string) {
    // Regular expression pattern to extract potential URLs from the string
    $url_pattern = '/\bhttps?:\/\/\S+\b/';

    // Use preg_match_all to find all potential URLs
    preg_match_all($url_pattern, $string, $url_matches);

    // Initialize an empty string to store the converted content
    $convertedContent = '';

    // Check if there are any URLs
    if (!empty($url_matches[0])) {
        // Loop through the potential URLs
        foreach ($url_matches[0] as $url) {
            // Check if it's a YouTube video link
            if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w\-]{11})(?:\S+)?/', $url, $youtube_match)) {
                $convertedContent .= "<iframe src='https://www.youtube.com/embed/{$youtube_match[1]}' allowfullscreen></iframe><br>";
            } else {
                // If it's not a YouTube video, assume it's a video file link
                $convertedContent .= "<video controls><source src='$url' type='video/mp4'></video>";
            }
        }
    }

    return $convertedContent;
}

# Remove "
function removeTags($string) {
    $string = strip_tags($string);
    $string = trim($string);
    return $string;
}

## Calculate time by given variable X
function calcTime($x) {
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $month = $week * 4;
    $year = $month * 12;

    if ($x == "minutes") {
        return $minute;
    }
    if ($x == "hours") {
        return $hour;
    }
    if ($x == "days") {
        return $day;
    }
    if ($x == "weeks") {
        return $week;
    }
    if ($x == "months") {
        return $month;
    }
    if ($x == "years") {
        return $year;
    }
}

$now = time();

## Create Cookie by variables
function createCookie($name, $value, $time, $time_type) {
    # A = Cookie name
    # B = Cookie value
    # C = Cookie time
    # D = Time type (1:seconds | 2:minutes | 3:hours | 4:days | 5:weeks | 6:months | 7:negative) [Optional]

    # Time = (86400 * 30) 86400 = 1 day | 1 month

    $cookie_name = $name;
    $cookie_value = $value;
    $cookie_time = $time;
    $cookie_time_type = $time_type;

    if ($cookie_time_type == "1") {
        $cookie_time;
    } else
    if ($cookie_time_type == "2") {
        $cookie_time = $cookie_time * calcTime("minutes");
    } else
    if ($cookie_time_type == "3") {
        $cookie_time = $cookie_time * calcTime("hours");
    } else
    if ($cookie_time_type == "4") {
        $cookie_time = $cookie_time * calcTime("days");
    } else
    if ($cookie_time_type == "5") {
        $cookie_time = $cookie_time * calcTime("weeks");
    } else
    if ($cookie_time_type == "6") {
        $cookie_time = $cookie_time * calcTime("months");
    } else
    if ($cookie_time_type == "7") {
        $cookie_time = "-1";
    } else {
        $cookie_time = "3";
    }

    $cookie_time = time() + $cookie_time;

    setcookie($cookie_name, $cookie_value, $cookie_time, "/");
}

# Get text in language - x = column name input | y = value | z = column name output
function language($x,$y,$z) {
    global $conn;
    if ($x == "id") {
        $attr = "WHERE `id`='$y'";
    } else
    if ($x == "name") {
        $attr = "WHERE `name`='$y'";
    }
    
    $getLangs = $conn->query("SELECT * FROM `countries` $attr");
    $LRow = $getLangs->fetch_assoc();

    return $LRow[$z];
}

# Get user data
function getUser($x, $y, $z) {
    # X = Lookup from column
    # Y = Lookup from column data
    # Z = Get column data result
    global $conn;

    $user = $conn->query("SELECT * FROM `users` WHERE `$x`='$y'");
    if ($user->num_rows == 0) {
        return "error";
    } else {
        $data = $user->fetch_assoc();
        return $data[$z];
    }
}

# Get group membership
function getGM($x, $y) {
    # X = Lookup from column
    # Y = Lookup from column data
    # Z = Get column data result
    global $conn;

    $user = $conn->query("SELECT * FROM `group_members` WHERE `group`='$x' AND `user`='$y'");
    if ($user->num_rows == 1) {
        return "ok";
    } else {
        return "error";
    }
}

# Check friendship
function checkFriendship($uid,$friend) {
    global $conn;

    $checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
    if ($checkFriendship->num_rows == 1) {
        $friendshipData = $checkFriendship->fetch_assoc();
        $status = $friendshipData['status'];

        if ($status == "accepted") {
            return "ok";
        } else {
            return "";
        }
    } else {
        return "";
    }
}

# Convert/Store emojis
function fixEmojis($x,$flip) {
    $emojiMap = array(
        '🙂' => ':)',
        '😁' => ':D',
        '😛' => ':P',
        '🙁' => ':(',
        '😉' => ';)',
        '😮' => ':O',
        '😘' => ':*',
        '❤️' => '<3',
        '😕' => ':/',
        '😐' => ':|',
        '🤫' => ':$',
        '👽' => ':o)',
        '😞' => ':-(',
        '😊' => ':-)',
        '😂' => ':-D',
        '😜' => ':-P',
        '😕' => ':-/',
        '😐' => ':-|',
        '😉' => ';-)',
        '😊' => '=)',
        '😃' => '=D',
        '😛' => '=P',
        '💩' => ':poop:',
        '🔥' => ':fire:',
        '🚀' => ':rocket:',
        '😀' => ':D1',
        '😃' => ':D2',
        '😄' => ':D3',
        '😁' => ':D4',
        '😆' => ':D5',
        '😅' => ':D6',
        '🤣' => ':D7',
        '😂' => ':D8',
        '🙂' => ':)1',
        '🙃' => ':)2',
        '🫠' => ':)3',
        '😉' => ';)1',
        '😊' => ':-)1',
        '😇' => ':angel:0',
        '🥰' => ':heart_eyes:0',
        '😍' => ':heart_eyes_cat:0',
        '🤩' => ':star_struck:0',
        '😘' => ':kissing_heart:0',
        '😗' => ':kissing:0',
        '☺️' => ':relaxed:0',
        '😚' => ':kissing_closed_eyes:0',
        '😙' => ':kissing_smiling_eyes:0',
        '🥲' => ':smiling_with_tear:0',
        '😋' => ':yum:0',
        '😛' => ':stuck_out_tongue:0',
        '😜' => ':stuck_out_tongue_winking_eye:0',
        '🤪' => ':zany_face:0',
        '😝' => ':stuck_out_tongue_closed_eyes:0',
        '🤑' => ':money_mouth_face:0',
        '🤗' => ':hugging:0',
        '🤭' => ':face_with_hand_over_mouth:0',
        '🫢' => ':hand_over_mouth:0',
        '🫣' => ':salivating_face:0',
        '🤫' => ':shushing_face:0',
        '🤔' => ':thinking:0',
        '🫡' => ':hand_on_chin:0',
        '🤐' => ':zipper_mouth_face:0',
        '🤨' => ':face_with_raised_eyebrow:0',
        '😐' => ':neutral_face:0',
        '😑' => ':expressionless:0',
        '😶' => ':no_mouth:0',
        '🫥' => ':smiling_imp:0',
        '😶‍🌫️' => ':face_in_clouds:0',
        '😏' => ':smirk:0',
        '😒' => ':unamused:0',
        '🙄' => ':roll_eyes:0',
        '😬' => ':grimacing:0',
        '😮‍💨' => ':face_exhaling:0',
        '🤥' => ':lying_face:0',
        '😌' => ':relieved:0',
        '😔' => ':pensive:0',
        '😪' => ':sleepy:0',
        '🤤' => ':drooling_face:0',
        '😴' => ':sleeping:0',
        '😷' => ':mask:0',
        '🤒' => ':face_with_thermometer:0',
        '🤕' => ':face_with_head_bandage:0',
        '🤢' => ':nauseated_face:0',
        '🤮' => ':face_vomiting:0',
        '🤧' => ':sneezing_face:0',
        '🥵' => ':hot_face:0',
        '🥶' => ':cold_face:0',
        '🥴' => ':woozy_face:0',
        '😵' => ':dizzy_face:0',
        '😵‍💫' => ':face_with_spiral_eyes:0',
        '🤯' => ':exploding_head:0',
        '🤠' => ':cowboy_hat_face:0',
        '🥳' => ':partying_face:0',
        '🥸' => ':disguised_face:0',
        '😎' => ':sunglasses:0',
        '🤓' => ':nerd_face:0',
        '🧐' => ':face_with_monocle:0',
        '😕' => ':confused:0',
        '🫤' => ':slightly_frowning_face:0',
        '😟' => ':worried:0',
        '🙁' => ':slightly_frowning_face:0',
        '☹️' => ':frowning_face:0',
        '😮' => ':open_mouth:0',
        '😯' => ':hushed:0',
        '😲' => ':astonished:0',
        '😳' => ':flushed:0',
        '🥺' => ':pleading_face:0',
        '🥹' => ':face_with_head_bandage:0',
        '😦' => ':frowning:0',
        '😧' => ':anguished:0',
        '😨' => ':fearful:0',
        '😰' => ':cold_sweat:0',
        '😥' => ':disappointed_relieved:0',
        '😢' => ':cry:0',
        '😭' => ':sob:0',
        '😱' => ':scream:0',
        '😖' => ':confounded:0',
        '😣' => ':persevere:0',
        '😞' => ':disappointed:0',
        '😓' => ':sweat:0',
        '😩' => ':weary:0',
        '😫' => ':tired_face:0',
        '🥱' => ':yawning_face:0',
        '😤' => ':triumph:0',
        '😡' => ':rage:0',
        '😠' => ':angry:0',
        '🤬' => ':face_with_symbols_over_mouth:0',
        '😈' => ':smiling_imp:0',
        '👿' => ':imp:0',
        '💀' => ':skull:0',
        '☠️' => ':skull_and_crossbones:0',
        '💩' => ':poop:0',
        '🤡' => ':clown_face:0',
        '👹' => ':japanese_ogre:0',
        '👺' => ':japanese_goblin:0',
        '👻' => ':ghost:0',
        '👽' => ':alien:0',
        '👾' => ':space_invader:0',
        '🤖' => ':robot_face:0',
        '💋' => ':kiss:0',
        '💌' => ':love_letter:0',
        '💘' => ':cupid:0',
        '💝' => ':gift_heart:0',
        '💖' => ':sparkling_heart:0',
        '💗' => ':heartpulse:0',
        '💓' => ':heartbeat:0',
        '💞' => ':revolving_hearts:0',
        '💕' => ':two_hearts:0',
        '💟' => ':heart_decoration:0',
        '❣️' => ':heavy_heart_exclamation:0',
        '💔' => ':broken_heart:0',
        '❤️‍🔥' => ':heart_on_fire:0',
        '❤️‍🩹' => ':mending_heart:0',
        '❤️' => ':heart:0',
        '🧡' => ':orange_heart:0',
        '💛' => ':yellow_heart:0',
        '💚' => ':green_heart:0',
        '💙' => ':blue_heart:0',
        '💜' => ':purple_heart:0',
        '🤎' => ':brown_heart:0',
        '🖤' => ':black_heart:0',
        '🤍' => ':white_heart:0',
        '🙈' => ':see_no_evil:0',
        '🙉' => ':hear_no_evil:0',
        '🙊' => ':speak_no_evil:0',
        '💯' => ':100:0',
        '💢' => ':anger:0',
        '💥' => ':boom:0',
        '💫' => ':dizzy:0',
        '💦' => ':sweat_drops:0',
        '💨' => ':dash:0',
        '🕳' => ':hole:0',
        '💣' => ':bomb:0',
        '💬' => ':speech_balloon:0',
        '🗨' => ':left_speech_bubble:0',
        '🗯' => ':right_anger_bubble:0',
        '💭' => ':thought_balloon:0',
        '💤' => ':zzz:0',
        '👋' => ':wave:0',
        '🤚' => ':raised_back_of_hand:0',
        '🖐' => ':raised_hand_with_fingers_splayed:0',
        '✋️' => ':raised_hand:0',
        '🖖' => ':vulcan_salute:0',
        '🫱' => ':palms_up_together:0',
        '🫲' => ':handshake:0',
        '🫳' => ':hand_with_index_and_middle_fingers_crossed:0',
        '🫴' => ':love_you_gesture:0',
        '👌' => ':ok_hand:0',
        '🤌' => ':pinched_fingers:0',
        '🤏' => ':pinching_hand:0',
        '✌️' => ':victory_hand:0',
        '🤞' => ':crossed_fingers:0',
        '🫰' => ':raised_hand_with_part_between_middle_and_ring_fingers:0',
        '🤟' => ':love_you_gesture:0',
        '🤘' => ':metal:0',
        '🤙' => ':call_me_hand:0',
        '👈' => ':point_left:0',
        '👉' => ':point_right:0',
        '👆' => ':point_up_2:0',
        '🖕' => ':middle_finger:0',
        '👇' => ':point_down:0',
        '☝️' => ':point_up:0',
        '🫵' => ':index_pointing_up_dark_skin_tone:0',
        '👍' => ':thumbs_up:0',
        '👎' => ':thumbs_down:0',
        '✊️' => ':fist_raised:0',
        '👊' => ':fist_oncoming:0',
        '🤛' => ':fist_left:0',
        '🤜' => ':fist_right:0',
        '👏' => ':clap:0',
        '🙌' => ':raised_hands:0',
        '🫶' => ':raising_hands:0',
        '👐' => ':open_hands:0',
        '🤲' => ':palms_up_together:0',
        '🤝' => ':handshake:0',
        '🙏' => ':pray:0',
        '✍️' => ':writing_hand:0',
        '👀' => ':eyes:0'
    );

    if ($flip == 1) {
        return strtr($x, array_flip($emojiMap));
    } else {
        return strtr($x, $emojiMap);
    }
}

## Check if device is mobile
function isMobile() {
    $isMobile = false;

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $mobileKeywords = array(
        'Mobile',
        'Android',
        'Silk/',
        'Kindle',
        'BlackBerry',
        'Opera Mini',
        'Opera Mobi'
    );

    foreach ($mobileKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            $isMobile = true;
            break;
        }
    }

    if ($isMobile) {
        return true;
    } else {
        return false;
    }
}

$msg = null;

# Create welcome cookie if not already exist and user is not logged in
if (!isset($_SESSION['user'])) {
    if (!isset($_COOKIE['welcomeScreen'])) {
        createCookie("welcomeScreen","Welcome to Skybyn","12","6");
    }
}

# Check if first time cookie exist
if (isset($_COOKIE['first-time'])) {
    $firstTime = false;
} else {
    $firstTime = true;
}

# Create cookie if first time
if (isset($_POST['first-time'])) {
    createCookie("first-time","","1","6");
    ?><meta http-equiv="Refresh" content="0; url='.'" /><?php
}

# Forgot password
if (isset($_POST['forgot'])) {
    $email = $_POST['email'];
    $reset = rand(100000, 999999);

    $checkEmail = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
    $userData = $checkEmail->fetch_assoc();
    $user_id = $userData['id'];
    if ($checkEmail->num_rows == 1) {
        $checkReset = $conn->query("SELECT * FROM `reset_codes` WHERE `userid`='$user_id'");
        if ($checkReset->num_rows == 1) {
            $setReset = $conn->query("UPDATE `reset_codes` SET `code`='$reset', `expiration_date`='$now' WHERE `userid`='$user_id'");
        } else {
            $setReset = $conn->query("INSERT INTO `reset_codes` (`userid`,`code`,`expiration_date`) VALUES ('$user_id','$reset','$now')");
        }

        $to = $email;
        $subject = "Skybyn - Password reset";
    
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@skybyn.no\r\n";
        $headers .= "Reply-To: no-reply@skybyn.no\r\n";
    
        $message = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <style>
                    :root {
                        --lightblue: rgba(183,231,236,1);
                        --blue: rgba(35,176,255,1);
                        --greyblue: rgba(42,106,133,1);
                    }

                    body {
                        margin: 0;
                        padding: 20px;
                        font-family: Arial, sans-serif;
                        color: white;
                        background: rgb(42,106,133);
                        background-size: cover;
                        background-position-y: bottom;
                        background-position-x: center;
                        background-attachment: fixed;
                        font-size: 16px;
                        line-height: 1.5;
                    }

                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        color: white;
                        text-align: center;
                        background: rgba(0,0,0,0.1);
                        padding: 30px;
                        border-radius: 5px;
                        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
                    }

                    .logo {
                        display: block;
                        width: 100px;
                        height: auto;
                        margin: 0 auto;
                        padding: 0;
                    }

                    .logo img {
                        display: block;
                        width: 100%;
                        height: auto;
                        margin: 0;
                        padding: 0;
                    }

                    h1 {
                        font-size: 24px;
                        margin-top: 20px;
                        margin-bottom: 20px;
                        text-align: center;
                    }

                    p {
                        margin-top: 0;
                        margin-bottom: 20px;
                    }

                    button {
                        background-color: #4CAF50;
                        color: #fff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        font-size: 16px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }

                    button:hover {
                        background-color: #3e8e41;
                    }

                    .code-box {
                        margin: 40px 0;
                    }
                    .code-box code {
                        width: auto;
                        background-color: rgba(0,0,0,0.1);
                        border: 1px solid rgba(0,0,0,0.1);
                        padding: 10px 20px;
                        overflow: auto;
                        line-height: 1.5;
                        font-size: 24px;
                        letter-spacing: 10px;
                        border-radius: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
                    <h1>You requested a password reset</h1>
                    <p>This code is to reset your password, if you did NOT request this, please ignore this email!</p>
                    <button onclick="window.location.href=\'https://skybyn.no/?reset='.$reset.'\'">Skip code</button>
                    <div class="code-box">
                        <code>'.$reset.'</code>
                    </div>
                </div>
            </body>
        </html>
        ';
    
        mail($to, $subject, $message, $headers);
        $msg = "If the e-mail address you provided is correct, we have sent a link for you to reset your password. Check your spam/inbox/trash.";
        createCookie("msg", $msg, "10", null);
        ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
    }
}

# Reset password
if (isset($_POST['reset'])) {
    $code = $_POST['code'];
    $password = hash("sha512", $_POST['password']);
    $cpassword = hash("sha512", $_POST['cpassword']);
    $salt = hash("sha512", rand());
    $pw = hash("sha512", $salt."_".$password);

    if ($password == $cpassword) {
        $checkCode = $conn->query("SELECT * FROM `reset_codes` WHERE `code`='$code'");
        $user_data = $checkCode->fetch_assoc();
        $user_id = $user_data['userid'];

        $conn->query("UPDATE `users` SET `password`='$pw', `salt`='$salt' WHERE `id`='$user_id'");
        $conn->query("DELETE FROM `reset_codes` WHERE `userid`='$user_id'");
        
        $msg = "Password reset successful!";
        createCookie("msg", $msg, "10", null);
        ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
    }
}

# User data
if (isset($_SESSION['user'])) {
    $firstTime = false;
    $uid = $_SESSION['user'];
    $UDRes = $conn->query("SELECT * FROM `users` WHERE `id`='$uid'");
    $UDRow = $UDRes->fetch_assoc();
    $email = $UDRow['email'];
    $username = $UDRow['username'];
    $rank = $UDRow['rank'];
    $dob = $UDRow['birth_date'];
    $reg_date = $UDRow['registration_date'];
    $first_name = $UDRow['first_name'];
    $middle_name = $UDRow['middle_name'];
    $last_name = $UDRow['last_name'];
    $title_name = $UDRow['title'];
    $avatar = "./".$UDRow['avatar'];
    $wallpaper = "./".$UDRow['wallpaper'];
    $wallpaper_margin = $UDRow['wallpaper_margin'];
    $country = $UDRow['country'];
    $ip = $UDRow['ip'];
    $darkmode = $UDRow['darkmode'];
    $minecraft = $UDRow['minecraft'];
    $habbo = $UDRow['habbo'];
    $fivem = $UDRow['fivem'];

    $verified = $UDRow['verified'];

    if ($verified == "0") {
        
    }
    
    $conn->query("UPDATE `users` SET `ip`='$newIP' WHERE `id`='$uid'");

    if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $previousUrl = $_SERVER['HTTP_REFERER'];
    } else {
        $previousUrl = "";
    }

    if (isset($_SESSION['gta'])) {
        if ($_SESSION['gta'] == "login") {
            ?><meta http-equiv="Refresh" content="0; url='https://skybyn.no/gta/ressurser" /><?php
        }
    }
    
    if ($habbo == "1") {
        $habbo_tgl = "checked";
    } else {
        $habbo_tgl = "";
    }
    if ($fivem == "1") {
        $fivem_tgl = "checked";
    } else {
        $fivem_tgl = "";
    }

    if ($darkmode == "0") {
        $color_one = $UDRow['color_one'];
    } else {
        $color_one = "";
    }

    $countryName = language('id',$country,'nicename');
    #$CName = strtolower($countryName);
    

    if ($avatar == "./") {
        $avatar = "./assets/images/logo_faded_clean.png";
    }

    if ($wallpaper == "./") {
        $wallpaper = "./assets/images/blank.png";
    }

    if ($first_name != "") {
        if ($last_name != "") {
            if ($middle_name != "") {
                $displayname = "$first_name $middle_name $last_name";
            } else {
                $displayname = "$first_name $last_name";
            }
        } else {
            $displayname = "$first_name";
        }
    } else {
        $displayname = "$username";
    }

    $logo = $avatar;

    if (empty($username)) {
        $_SESSION['username'];
    }

    ## Wallet
    $getWallet = $conn->query("SELECT * FROM `wallets` WHERE `user`='$uid'");
    $countWallets = $getWallet->num_rows;

    if ($countWallets == 0) {
        $conn->query("INSERT INTO `wallets` (`user`,`wallet`) VALUES ('$uid','0')");
    }
    
    $myWallet = $getWallet->fetch_assoc();
    $wallet = $myWallet['wallet'];
} else {
    if (isset($_COOKIE['logged'])) {
        $user = $_COOKIE['logged'];
        $checkUser = $conn->query("SELECT * FROM `users` WHERE `id`='$user'");
        $countUsers = $checkUser->num_rows;
        if ($countUsers == 1) {
            $_SESSION['user'] = $user;
            ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
        } else {
            createCookie("logged","","0","7"); # 7 = -1
        }
    }
    if (isset($_GET['ref'])) {
        if ($_GET['ref'] == "gta_login") {
            $_SESSION['gta'] = "login";
        }
    }
}

# Update avatar
if (isset($_POST['update_avatar'])) {
    $target_dir = "uploads/avatars/$uid/";
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["avatar"]["tmp_name"]);
      if($check !== false) {
        $msg = "File is an image - " . $check["mime"] . ".";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 1;
      } else {
        $msg = "File is not an image.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
      }
    }

    // Check if user folder already exists
    if (!file_exists($target_dir)) {
      mkdir($target_dir);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $msg = "Sorry, file already exists.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["avatar"]["size"] > 4194304) { // 4MB
        $msg = "Sorry, your file is too large.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg = "Sorry, your file was not uploaded.";
        createCookie("msg", $msg, "10", null);
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        $q = "UPDATE `users` SET `avatar`='$target_file' WHERE `id`='$uid'";
        mysqli_query($conn, $q);
      } else {
        $msg = "Sorry, there was an error uploading your file.";
        createCookie("msg", $msg, "10", null);
      }
    }
}

# Update wallpaper
if (isset($_POST['update_wallpaper'])) {
    $target_dir = "uploads/wallpapers/$uid/";
    $target_file = $target_dir . basename($_FILES["wallpaper"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["wallpaper"]["tmp_name"]);
      if($check !== false) {
        $msg = "File is an image - " . $check["mime"] . ".";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 1;
      } else {
        $msg = "File is not an image.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
      }
    }

    // Check if user folder already exists
    if (!file_exists($target_dir)) {
      mkdir($target_dir);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $msg = "Sorry, file already exists.";
    createCookie("msg", $msg, "10", null);
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["wallpaper"]["size"] > 20971520) { // 20MB
        $msg = "Sorry, your file is too large.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg = "Sorry, your file was not uploaded.";
        createCookie("msg", $msg, "10", null);
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["wallpaper"]["tmp_name"], $target_file)) {
        $q = "UPDATE `users` SET `wallpaper`='$target_file' WHERE `id`='$uid'";
        mysqli_query($conn, $q);
      } else {
        $msg = "Sorry, there was an error uploading your file.";
        createCookie("msg", $msg, "10", null);
      }
    }
}

if (isset($_POST['update_account'])) {
    $newemail = $_POST['email'];
    $newusername = $_POST['username'];
    $dob = $_POST['dob'];
    
    if ($newemail != $email) {
        $checkEmail = mysqli_query($conn, "SELECT * FROM `users` WHERE `email`='$newemail'");
        $count = mysqli_num_rows($checkEmail);
        if ($count == 0) {
            $conn->query("UPDATE `users` SET `email`='$newemail' WHERE `id`='$uid'");
        }
    }
    if ($newusername != $username) {
        $checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$newusername'");
        $count = mysqli_num_rows($checkUsername);
        if ($count == 0) {
            $conn->query("UPDATE `users` SET `username`='$newusername' WHERE `id`='$uid'");
        }
    }
    if (!empty($dob)) {
        $conn->query("UPDATE `users` SET `birth_date`='$dob' WHERE `id`='$uid'");
    }

    header("location: ./settings");
}

if (isset($_POST['update_name'])) {
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];
    $tname = $_POST['title_name'];

    if (!empty($fname)) {
        $conn->query("UPDATE `users` SET `first_name`='$fname' WHERE `id`='$uid'");
        $conn->query("UPDATE `users` SET `middle_name`='$mname' WHERE `id`='$uid'");
    }
    if (!empty($lname)) {
        $conn->query("UPDATE `users` SET `last_name`='$lname' WHERE `id`='$uid'");
    }
    if (!empty($tname)) {
        $conn->query("UPDATE `users` SET `title`='$tname' WHERE `id`='$uid'");
    }

    header("location: ./settings");
}

if (isset($_POST['update_password'])) {
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if (!empty($password)) {
        if ($password == $cpassword) {
            $password = hash("sha512", $password);
            $pw = hash("sha512", $salt."_".$password);
            $conn->query("UPDATE `users` SET `password`='$pw' WHERE `id`='$uid'");
        }
    }

    header("location: ./settings");
}

if (isset($_POST['update_pin'])) {
    $pinv = $_POST['pinv'];
    $pin = $_POST['pin'];
    $cpin = $_POST['cpin'];

    if (!empty($pin)) {
        if ($pin == $cpin) {
            $pincode = hash("sha512", $pin);
            $conn->query("UPDATE `users` SET `pin_v`='$pinv', `pin`='$pincode' WHERE `id`='$uid'");
        }
    }

    header("location: ./settings");
}

# Share | Create new post
if (isset($_POST['share'])) {
    $text = $_POST['text'];
    $created = $now;

    $conn->query("INSERT INTO `posts` (`user`,`content`,`created`) VALUES ('$uid','$text','$created')");
    $getPostData = $conn->query("SELECT * FROM `posts` WHERE `user`='$uid' AND `created`='$created'");
    $postData = $getPostData->fetch_assoc();
    $post_id = $postData['id'];
    
    $target_dir = "uploads/post/$uid/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $msg = "File is an image - " . $check["mime"] . ".";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 1;
    } else {
        $msg = "File is not an image.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check if user folder already exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $msg = "Sorry, file already exists.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["file"]["size"] > 4194304) { // 4MB
        $msg = "Sorry, your file is too large.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        createCookie("msg", $msg, "10", null);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg = "Sorry, your file was not uploaded.";
        createCookie("msg", $msg, "10", null);
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $conn->query("INSERT INTO `uploads` (`user`,`post`,`file_url`) VALUES ('$uid','$post_id','$target_dir')");
        } else {
        $msg = "Sorry, there was an error uploading your file.";
        createCookie("msg", $msg, "10", null);
        }
    }
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

# Delete post
if (isset($_POST['deletePost'])) {
    $post = $_POST['post_id'];

    $conn->query("DELETE FROM `posts` WHERE `id`='$post'");
    $conn->query("DELETE FROM `comments` WHERE `post`='$post'");
}


### GTA

if (isset($_POST['acceptRes'])) {
    $ResID = $_POST['acceptRes'];
    $conn->query("UPDATE `gta_resources` SET `accepted`='1' WHERE `id`='$ResID'");
}
if (isset($_POST['addedRes'])) {
    $ResID = $_POST['addedRes'];
    $conn->query("UPDATE `gta_resources` SET `added`='1' WHERE `id`='$ResID'");
}

?>