<?php include_once "conn.php";

$domend = $_SERVER['HTTP_HOST'];
$extension = substr($domend, -3);
if ($extension == 'com') {
    $domend = '.no';
} elseif ($extension == 'no') {
    $domend = '.com';
}

if (isset($_COOKIE['qr_login'])) {
    $uid = $_COOKIE['qr_login'];
    $checkUser = $conn->query("SELECT * FROM `users` WHERE `id`='$uid'");
    if ($checkUser->num_rows == 1) {
        $token = $checkUser->fetch_assoc()['token'];
        $_SESSION['user'] = $uid;
        setcookie('qr_login', '', time() - 3600, '/');
        setcookie('qr', '', time() - 3600, '/');
        createCookie("login_token",$token,"1","6");
    }
}

if (!isset($_SESSION['user'])) {
    if (isset($_COOKIE['login_token'])) {
        $token = $_COOKIE['login_token'];
        $checkToken = $conn->query("SELECT * FROM `users` WHERE `token`='$token'");
        if ($checkToken->num_rows == 1) {
            $tokenData = $checkToken->fetch_assoc();
            $uid = $tokenData['id'];
            $_SESSION['user'] = $uid;
        } else {
            setcookie('login_token', '', time() - 3600, '/');
        }
    }
}

$beta = false;
if (isset($_COOKIE['beta'])) {
    $betaKey = $_COOKIE['beta'];
    $checkBeta = $conn->query("SELECT * FROM `beta_access` WHERE `key`='$betaKey'");
    if ($checkBeta->num_rows == 1) {
        $betaData = $checkBeta->fetch_assoc();
        $uid = $betaData['user_id'];
        $beta = $betaKey;
    } else {
        setcookie('beta', '', time() - 3600, '/');
    }
}

if (isset($_GET['betaaccess'])) {
    $code = $_GET['betaaccess'];
    $checkCode = $conn->query("SELECT `key` FROM `beta_access` WHERE `key`='$code'");
    if ($checkCode->num_rows == 1) {
        createCookie('beta', $code, 1, 5); // 1 week
        ?><script>window.location.href = "../";</script><?php
    }
}

# Encrypt / Decrypt
function encrypt($text) {
    $key = substr(hash('sha512', SECRET_KEY, true), 0, 32);
    $iv = openssl_random_pseudo_bytes(16);
    $pad_length = 16 - (strlen($text) % 16);
    $text .= str_repeat(chr($pad_length), $pad_length);
    $encrypted = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($encrypted === false) {
        die("Encryption failed");
    }

    return base64_encode($iv . $encrypted);
}

function decrypt($text) {
    $key = substr(hash('sha512', SECRET_KEY, true), 0, 32);
    $data = base64_decode($text);

    if ($data === false || strlen($data) < 17) {
        die("Base64 decoding failed or data too short");
    }

    $iv = substr($data, 0, 16);
    $encrypted_data = substr($data, 16);
    $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        die("Decryption failed. Possible causes: wrong key, IV mismatch, corrupted data.");
    }

    $pad_length = ord(substr($decrypted, -1));

    if ($pad_length < 1 || $pad_length > 16) {
        die("Invalid padding detected");
    }

    return substr($decrypted, 0, -$pad_length);
}

function isNotEncrypted($string) {
    if (base64_encode(base64_decode($string, true)) !== $string) {
        return true; // Not Base64 encoded, so definitely not encrypted
    }

    $decoded = base64_decode($string);
    if (strlen($decoded) < 17) {
        return true; // Too short to contain IV + encrypted data
    }

    $iv = substr($decoded, 0, 16);
    $encrypted = substr($decoded, 16);
    
    $key = substr(hash('sha512', SECRET_KEY, true), 0, 32);
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        return true; // Decryption failed, meaning it's not an encrypted string
    }

    // Validate padding
    $pad_length = ord($decrypted[-1]);
    if ($pad_length < 1 || $pad_length > 16) {
        return true; // Invalid padding
    }

    $padding = substr($decrypted, -$pad_length);
    if ($padding !== str_repeat(chr($pad_length), $pad_length)) {
        return true; // Incorrect padding
    }

    return false; // It's encrypted
}

# Get full url
function fullUrl() {
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $fullUrl = $scheme . '://' . $host . $requestUri;
    return $fullUrl;
}
function domain($url) {
    $url = parse_url($url, PHP_URL_HOST);
    return $url;
}
function page($url) {
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];
    $lastPart = basename($path);
    return $lastPart;
}

$domain = domain(fullUrl());
$currentPage = page(fullUrl());

$devDomain = 'dev.skybyn.no';
if ($domain == $devDomain) {
    $homepage = "https://dev.skybyn$domend/";
} else {
    $homepage = "https://skybyn$domend/";
}

# Get specified system_data
function skybyn($valueOf) {
    global $conn;
    global $avatar;
    global $username;

    $systemData = $conn->query("SELECT * FROM `system_data` WHERE `data`='$valueOf'");
    $SDRow = $systemData->fetch_assoc();

    if ($valueOf == "logo" && isset($_SESSION['user'])) {
        return $avatar;
    } else
    if ($valueOf == "title" && isset($_SESSION['user'])) {
        return $SDRow['text']." - ".$username;
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
    // Check for proxy headers first
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; // Get the first IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return trim($ip);
        }
    }

    if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // Fallback to REMOTE_ADDR
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function geoData($path = null) {
    $ip = getIP();
    $apiUrl = "https://ipwhois.app/json/$ip";

    $response = @file_get_contents($apiUrl);

    if ($response === false) {
        return "Error: Unable to fetch data from API.";
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: Invalid JSON response.";
    }

    if (isset($data['success']) && !$data['success']) {
        return "Error: " . ($data['message'] ?? "Unknown error.");
    }

    if ($path) {
        $keys = explode('.', $path);
        $tempData = $data;

        foreach ($keys as $key) {
            if (isset($tempData[$key])) {
                $tempData = $tempData[$key];
            } else {
                return "Error: Key '$key' not found.";
            }
        }

        return $tempData;
    }

    // Return full data if no specific path is requested
    return $data;
}

// Example of usage
// echo geoData("city"); // Outputs city name, e.g., "Oslo"
// echo geoData("region"); // Outputs region name, e.g., "Oslo County"


# Create cookie with country information
if (!isset($_COOKIE['country'])) {
    $country = geoData("country_name");
    #createCookie("country",$country, "1","6");
}

function extractUrls($text) {
    $urlPattern = '/\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/i';
    $urls = array();
    if (preg_match_all($urlPattern, $text, $matches)) {
        $urls = $matches[0];
    }
    return $urls;
}

function getLinkPreview($url) {
    $response = @file_get_contents($url);
    if (!$response) {
        return ['error' => 'Unable to fetch content'];
    }

    $doc = new DOMDocument();
    @$doc->loadHTML($response);

    // Initialize variables
    $title = $description = $ogImage = $favicon = '';

    // Get title from <title> tag or <meta property="og:title">
    $titleTag = $doc->getElementsByTagName('title')->item(0);
    $title = $titleTag ? trim($titleTag->nodeValue) : '';

    foreach ($doc->getElementsByTagName('meta') as $meta) {
        if ($meta instanceof DOMElement) {
            $prop = $meta->getAttribute('property');
            $name = $meta->getAttribute('name');
            $content = $meta->getAttribute('content');

            // Get Open Graph Title if available
            if ($prop === 'og:title' && empty($title)) {
                $title = trim($content);
            }

            // Get Open Graph Description
            if ($prop === 'og:description' || strtolower($name) === 'description') {
                $description = trim($content);
            }

            // Get Open Graph Image
            if ($prop === 'og:image' && empty($ogImage)) {
                $ogImage = $content;
            }

            // Get Twitter Image if OG Image is missing
            if ($name === 'twitter:image' && empty($ogImage)) {
                $ogImage = $content;
            }
        }
    }

    // Get favicon from <link rel="icon">
    foreach ($doc->getElementsByTagName('link') as $link) {
        if ($link instanceof DOMElement) {
            $rel = strtolower($link->getAttribute('rel'));
            if (strpos($rel, 'icon') !== false && $link->hasAttribute('href')) {
                $favicon = $link->getAttribute('href');
                break;
            }
        }
    }

    // If favicon is missing, use Google Favicon API
    if (empty($favicon)) {
        $favicon = 'https://www.google.com/s2/favicons?sz=128&domain=' . parse_url($url, PHP_URL_HOST);
    }

    // If featured image is missing, try to find first <img>
    if (empty($ogImage)) {
        $images = $doc->getElementsByTagName('img');
        if ($images->length > 0) {
            $ogImage = $images->item(0)->getAttribute('src');
        } else {
            $ogImage = '../assets/images/logo_faded_clean.png';
        }
    }

    return [
        'title' => $title ?: 'No Title Found',
        'description' => $description ?: 'No description available',
        'image' => $ogImage ?: '',
        'favicon' => $favicon,
        'url' => $url
    ];
}

function getLinkData($url) {
    $ageRestrictedUrls = [
        'pornhub.com', 'xvideos.com', 'xhamster.com', 'redtube.com', 'youporn.com',
        'xnxx.com', 'brazzers.com', 'chaturbate.com', 'livejasmin.com', 'myfreecams.com',
        'camsoda.com', 'stripchat.com', 'bongacams.com', 'cam4.com', 'flirt4free.com',
        'imlive.com', 'streamate.com', 'manyvids.com', 'onlyfans.com', 'justfor.fans',
        'fanpage.com', 'fansly.com', 'loyalfans.com', 'seegore.com', 'documentingreality.com'
    ];

    $tags = get_meta_tags($url);
    
    // Parse the HTML to get title and featured image
    $html = file_get_contents($url);
    preg_match("/<title>(.*?)<\/title>/is", $html, $title_matches);
    preg_match('/<meta property="og:image" content="(.*?)"/is', $html, $image_matches);
    preg_match('/<link rel="icon" href="(.*?)"/is', $html, $logo_matches);
    
    $title = $title_matches[1] ?? '';
    $description = $tags['description'] ?? '';
    $featured_image = $image_matches[1] ?? '';
    $logo = $logo_matches[1] ?? '';
    
    return [
        'title' => $title,
        'description' => $description,
        'featured_image' => $featured_image,
        'logo' => $logo,
    ];
}

function cleanUrls($url) {
    $urlPattern = '/\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/i';
    $cleanedUrl = preg_replace($urlPattern, '', $url);
    return $cleanedUrl;
}

function shortenUrlToDomain($url) {
    $urlPattern = '/\b(?:https?):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/i';
    $url = preg_replace_callback($urlPattern, function($match) {
        $text = $match[0];
        if (!empty(shortenUrlToDomain($text))) {
            return shortenUrlToDomain($text);
        }
    }, $url);
    
    $parsedUrl = parse_url($url);

    if (isset($parsedUrl['host'])) {
        $domain = $parsedUrl['host'];
        $domain = preg_replace('/^www\./', '', $domain);

        $pageData = getMetaData($url);
        if (!empty($title)) {
            $title = $pageData['title'];
        } else {
            $title = $domain;
        }
        if (!empty($description)) {
            $description = $pageData['description'];
        } else {
            $description = "";
        }
        if (!empty($image)) {
            $image = $pageData['image'];
        } else {
            $image = "";
        }
        if (!empty($favicon)) {
            $favicon = $pageData['favicon'];
        } else {
            $favicon = "";
        }

        $google_favicon = 'https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://'.$domain.'&size=128';

        if ($domain == in_array($domain, array('skybyn.com', 'skybyn.no'))) {
            $title = 'Skybyn';
        }
        if ($domain == 'google.com') {
            $query = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';
            parse_str($query, $params);
            $q = isset($params['q']) ? $params['q'] : '';
            $title = 'Google Search';
            $description = $q;
        }

        if ($image == "") {
            if ($favicon == "") {
                $image = $google_favicon;
            } else {
                $image = $favicon;
            }
        }
        
        $preview = '<div class="post_link_preview" onclick="window.open(\''.$url.'\', \'_blank\')">';
        $preview_image = '<div class="post_link_preview_image">';
        $preview_info = '</div><div class="post_link_preview_info">';
        $preview_title = '<div class="post_link_preview_title">';
        $preview_description = '</div><div class="post_link_preview_description">';
        $preview_end = '</div></div></div>';
        
        $preview_image .= '<img src="'.$image.'" alt="'.$title.'">';
        $preview_title .= $title;
        $preview_description .= $description;

        return $preview.$preview_image.$preview_info.$preview_title.$preview_description.$preview_end;
    } else {
        return $url;
    }
}

function getMetaData($url) {
    $htmlContent = file_get_contents($url);
    $doc = new DOMDocument();
    @$doc->loadHTML($htmlContent);
    $metaData = [];
    $metaTags = $doc->getElementsByTagName('meta');
    foreach ($metaTags as $meta) {
        if ($meta instanceof DOMElement && $meta->hasAttribute('name')) {
            $metaName = $meta->getAttribute('name');
            $metaContent = $meta->getAttribute('content');
            $metaData[$metaName] = $metaContent;
        }
    }
    $title = $doc->getElementsByTagName('title');
    if ($title->length > 0) {
        $metaData['title'] = $title->item(0)->textContent;
    }

    return $metaData;
}

# Check if URL points to a video platform
function isVideoPlatformUrl($url) {
    // Define an array of video platforms to check
    $videoPlatforms = array(
        "youtu.be",
        "youtube.com",
        "vimeo.com",
        "tiktok.com",
        "twitch.tv",
        "dailymotion.com",
        "instagram.com/igtv",
        "d.tube",
        "9gag.com",
        "ted.com",
        "flickr.com"
    );

    // Check if the URL matches any of the video platforms
    foreach ($videoPlatforms as $platform) {
        $parsedUrl = parse_url($url);
        if (strpos($url, $platform) !== false && isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            return true;
        }
    }

    return false;
}

# Display video frame with youtube code when text contains youtube url
function convertVideo($string) {
    $patterns = array(
        '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w\-]{11})(?:\S+)?/' => 'youtube',
        '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com\/)(\d+)(?:\S+)?/' => 'vimeo',
        '/(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/)(@[\w\-]+\/video\/\d+)(?:\S+)?/' => 'tiktok',
        '/(?:https?:\/\/)?(?:www\.)?(?:twitch\.tv\/)([\w\-]+)(?:\S+)?/' => 'twitch',
        '/(?:https?:\/\/)?(?:www\.)?(?:dailymotion\.com\/video\/)([\w\-]+)(?:\S+)?/' => 'dailymotion',
        '/(?:https?:\/\/)?(?:www\.)?(?:instagram\.com\/tv\/)([\w\-]+)(?:\S+)?/' => 'igtv',
        '/(?:https?:\/\/)?(?:www\.)?(?:d\.tube\/)([\w\-]+)(?:\S+)?/' => 'dtube',
        '/(?:https?:\/\/)?(?:www\.)?(?:9gag\.com\/gag\/)([\w\-]+)(?:\S+)?/' => '9gag',
        '/(?:https?:\/\/)?(?:www\.)?(?:ted\.com\/talks\/)([\w\-]+)(?:\S+)?/' => 'ted',
        '/(?:https?:\/\/)?(?:www\.)?(?:flickr\.com\/photos\/[\w\-]+\/)(\d+)(?:\S+)?/' => 'flickr'
    );

    $output = "";

    foreach ($patterns as $pattern => $platform) {
        preg_match_all($pattern, $string, $matches);

        foreach ($matches[1] as $match) {
            $iframe = generateIframe($platform, $match);
            $output .= $iframe;
        }
    }

    return $output;
}

function generateIframe($platform, $videoId) {
    $iframe = "";

    switch ($platform) {
        case 'youtube':
            $iframe = "<iframe src='https://www.youtube.com/embed/$videoId' allowfullscreen></iframe>";
            break;
        case 'vimeo':
            $iframe = "<iframe src='https://player.vimeo.com/video/$videoId' allowfullscreen></iframe>";
            break;
        case 'tiktok':
            $iframe = "<iframe src='https://www.tiktok.com/embed/$videoId' allowfullscreen></iframe>";
            break;
        case 'twitch':
            $iframe = "<iframe src='https://www.twitch.tv/videos/$videoId' allowfullscreen></iframe>";
            break;
        case 'dailymotion':
            $iframe = "<iframe src='https://www.dailymotion.com/embed/video/$videoId' allowfullscreen></iframe>";
            break;
        case 'igtv':
            $iframe = "<iframe src='https://www.instagram.com/tv/$videoId/embed/' allowfullscreen></iframe>";
            break;
        case 'dtube':
            $iframe = "<iframe src='https://d.tube/#!/v/$videoId' allowfullscreen></iframe>";
            break;
        case '9gag':
            $iframe = "<iframe src='https://9gag.com/gag/$videoId' allowfullscreen></iframe>";
            break;
        case 'ted':
            $iframe = "<iframe src='https://www.ted.com/talks/$videoId' allowfullscreen></iframe>";
            break;
        case 'flickr':
            $iframe = "<iframe src='https://www.flickr.com/photos/$videoId/play/orig/$videoId/' allowfullscreen></iframe>";
            break;
    }

    return $iframe;
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
function timeAgo($timestamp) {
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60) {
        return $diff . " seconds ago";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " minutes ago";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . " hours ago";
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . " days ago";
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . " weeks ago";
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . " months ago";
    } else {
        $years = floor($diff / 31536000);
        return $years . " years ago";
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
        $attr = "WHERE `country_name`='$y'";
    }
    
    $getLangs = $conn->query("SELECT * FROM `countries` $attr");
    $LRow = $getLangs->fetch_assoc();

    return isset($LRow[$z]) ? $LRow[$z] : '';
}

# Get user data
function getUser($column, $data, $return) {
    # X = Lookup from column
    # Y = Lookup from column data
    # Z = Get column data result
    global $conn;

    $user = $conn->query("SELECT * FROM `users` WHERE `$column`='$data'");
    if ($user->num_rows == 0) {
        return "error";
    } else {
        $result = $user->fetch_assoc();
        if ($column == "email") {
            return decrypt($result[$return]);
        } else {
            return $result[$return];
        }
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

# Send notification
function notify($to, $from, $type) {
    global $conn;
    $now = time();
    if ($from == "system") {
        $from = "0";
    }
    $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$to','$from','$now','$type')");
}

# Friendship actions
function friendship($uid, $friend, $action) {
    global $conn;
    $now = time();

    $checkStatus = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
    $friendshipStatus = $checkStatus->fetch_assoc();

    $statusS = "sent";
    $statusR = "received";
    $statusA = "accepted";
    $statusD = "declined";
    $statusI = "ignored";
    $statusB = "block";
    $statusUnf = "unfriend";
    $statusUnb = "unblock";

    if ($checkStatus->num_rows == 0) { // IF NO RECORDS
        if ($action == "send") {
            $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$uid', '$friend', '$statusS', '$now')");
            $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$friend', '$uid', '$statusR', '$now')");
            $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$friend','$uid','$now','friend_request')");
            echo "Friend request sent.";
        } else {
            echo "Error sending";
        }
    } else {
        $status = $friendshipStatus['status'];

        if ($action == "accept") {
            if ($status == $statusR) {
                $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
                $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$uid' AND `friend_id`='$friend'");
                $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$friend','$uid','$now','friend_accepted')");
                echo "Friend request accepted.";
            } else {
                echo "Error accepting";
            }
        } elseif ($action == "ignore") {
            if ($status == $statusR) {
                $conn->query("UPDATE `friendship` SET `status`='sent' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
                $conn->query("DELETE FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
                echo "Friend request ignored.";
            } else {
                echo "Error ignoring";
            }
        } elseif ($action == "cancel") {
            if ($status == $statusS) {
                $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
                echo "Friend request canceled.";
            } else {
                echo "Error canceling";
            }
        } elseif ($action == "unfriend") {
            if ($status == $statusA) {
                $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
                echo "Unfriended.";
            } else {
                echo "Error unfriending";
            }
        } elseif ($action == "block") {
            if ($status == $statusA) {
                $conn->query("UPDATE `friendship` SET `status`='$statusB' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
                echo "Blocked.";
            } else {
                echo "Error blocking";
            }
        } elseif ($action == "unblock") {
            if ($status == $statusB) {
                $conn->query("UPDATE `friendship` SET `status`='$statusUnb' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
                echo "Unblocked.";
            } else {
                echo "Error unblocking";
            }
        } elseif ($action == "referral") {
            $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$uid', '$friend', 'friends', '$now')");
            $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$friend', '$uid', 'friends', '$now')");
        } else {
            echo "Invalid action.";
        }
    }
}
# Check friendship
function checkFriendship($user,$friend) {
    global $conn;

    $checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$friend' AND `friend_id`='$user'");
    if ($checkFriendship->num_rows == 1) {
        $friendshipData = $checkFriendship->fetch_assoc();
        $status = $friendshipData['status'];

        if ($status == "friends") {
            return "friends";
        } else {
            if ($status == "blocked") {
                return "blocked";
            } else {
                return "";
            }
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
function isMobile($userAgent) {
    $mobileKeywords = [
        'Mobile',
        'Android',
        'Silk/',
        'Kindle',
        'BlackBerry',
        'Opera Mini',
        'Opera Mobi'
    ];

    foreach ($mobileKeywords as $keyword) {
        if (is_string($userAgent) && $userAgent !== null) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }
    }

    return false;
}

// Usage
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

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
}

# Forgot password
if (isset($_POST['forgot'])) {
    $username = $_POST['username'];
    $reset = rand(100000, 999999);
    $msg = "If the username you provided exists, we have sent you an email with a link to reset your password. Remember to check your spam/trash.";

    $checkUser = $conn->query("SELECT * FROM `users` WHERE `username`='$username'");
    if ($checkUser->num_rows == 1) {
        $userData = $checkUser->fetch_assoc();
        $user_id = $userData['id'];
        $email = decrypt($userData['email']);
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
                    <button onclick="window.location.href=\'https://skybyn.no/reset?code'.$reset.'\'">Reset now</button>
                    <div class="code-box">
                        <code>'.$reset.'</code>
                    </div>
                </div>
            </body>
        </html>
        ';
    
        //mail($to, $subject, $message, $headers);
        createCookie("msg", $msg, "10", null);
        ?><script>window.location.href = "../reset";</script><?php
    } else {
        createCookie("msg", $msg, "10", null);
        ?><script>window.location.href = "../forgot";</script><?php
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
        ?><script>window.location.href = "../";</script><?php
    }
}

# User data
if (isset($_SESSION['user'])) {
    $firstTime = false;
    $uid = $_SESSION['user'];
    $UDRes = $conn->query("SELECT * FROM `users` WHERE `id`='$uid'");
    if ($UDRes->num_rows == 0) {
        session_destroy();
        ?><script>window.location.href = "../";</script><?php
    }
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
    $token = $UDRow['token'];
    $avatar = "../".$UDRow['avatar'];
    $wallpaper = "../".$UDRow['wallpaper'];
    $wallpaper_margin = $UDRow['wallpaper_margin'];
    $country = $UDRow['country'];
    $darkmode = $UDRow['darkmode'];
    $last_url = $UDRow['last_url'];
    $verified = $UDRow['verified'];

    $currentUrl = $_SERVER['REQUEST_URI'];

    if (isNotEncrypted($email)) {
        $email;
    } else {
        $email = decrypt($email);
    }
    if ($title_name != "") {
        if (isNotEncrypted($title_name)) {
            $title_name;
        } else {
            $title_name = decrypt($title_name);
        }
    }
    if (isNotEncrypted($first_name)) {
        $first_name;
    } else {
        $first_name = decrypt($first_name);
    }
    if (isNotEncrypted($middle_name)) {
        $middle_name;
    } else {
        $middle_name = decrypt($middle_name);
    }
    if (isNotEncrypted($last_name)) {
        $last_name;
    } else {
        $last_name = decrypt($last_name);
    }

    $checkBetaAccess = $conn->query("SELECT `key` FROM `beta_access` WHERE `user_id`='$uid'");
    $beta = $checkBetaAccess && $checkBetaAccess->num_rows > 0;

    if ($last_url != $currentUrl) {
        $returnTo = $last_url;
    } else {
        $returnTo = "";
    }

    if ($token == "") {
        $token = hash("sha512", rand(100000, 999999)."_".$uid."_".time());
        $conn->query("UPDATE `users` SET `token`='$token' WHERE `id`='$uid'");
    }

    if (!isset($_COOKIE['login_token'])) {
        createCookie("login_token",$token,"10","2");
    }

    if (!isset($_COOKIE['sui'])) {
        createCookie("sui",$uid,"1","7");
    }

    if (isset($_COOKIE['qr'])) {
        $code = $_COOKIE['qr'];
        if (file_exists("qr/temp/$code.png")) {
            unlink("qr/temp/$code.png");
        }
        setcookie("qr", "", time() - 3600);
    }

    if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $previousUrl = $_SERVER['HTTP_REFERER'];
    } else {
        $previousUrl = "";
    }

    function referralCode() {
        global $conn;
        global $uid;

        $code = rand(10000000, 99999999);
        $in_five_min = strtotime(' +5 minutes ');

        $checkReferral = $conn->query("SELECT * FROM `referral_code` WHERE `user`='$uid'");
        if ($checkReferral->num_rows == 1) {
            $referrals = $checkReferral->fetch_assoc();
            $code = $referrals['referral_code'];
            $date = $referrals['created'];

            if ($date < time()) {
                $stmt = $conn->prepare("UPDATE `referral_code` SET `referral_code` = ?, `created` = ? WHERE `user` = ?");
                $stmt->bind_param("iii", $code, $in_five_min, $uid);
                $stmt->execute();
                return $code;
            } else {
                return $code;
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO `referral_code` (`referral_code`,`created`,`user`) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $code, $in_five_min, $uid);
            $stmt->execute();
            return $code;
        }
    }

    $referral = referralCode();

    $countryName = language('id',$country,'country_name');
    if ($countryName) {
        $CName = strtolower($countryName);
    }
    

    if ($avatar == "../") {
        $avatar = "../assets/images/logo_faded_clean.png";
    }

    if ($wallpaper == "../") {
        $wallpaper = "../assets/images/blank.png";
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

    $myOwnedGroups = $conn->query("SELECT * FROM `groups` WHERE `owner`='$uid'");

    ## Wallet
    $getWallet = $conn->query("SELECT * FROM `wallets` WHERE `user`='$uid'");
    $countWallets = $getWallet->num_rows;
    if ($countWallets == 0) {
        $createWallet = $conn->query("INSERT INTO `wallets` (`user`,`wallet`) VALUES ('$uid','0')");
        $getWallet = $conn->query("SELECT * FROM `wallets` WHERE `user`='$uid'");
        $myWallet = $getWallet->fetch_assoc();
        $wallet = $myWallet['wallet'];
    } else {
        $myWallet = $getWallet->fetch_assoc();
        $wallet = $myWallet['wallet'];
    }
    
    // Chatting functionalities - DO NOT CHANGE
    if (isset($_POST['start_chat'])) { // Start chat
        $fid = $_POST['friend_id'];
        if (strlen($fid) > 0) {
            $checkChat = $conn->prepare("SELECT * FROM `active_chats` WHERE `user`=? AND `friend`=?");
            $checkChat->bind_param("ii", $uid, $fid);
            $checkChat->execute();
            $result = $checkChat->get_result();
            if ($result->num_rows == 0) {
                $insertChat = $conn->prepare("INSERT INTO `active_chats` (`user`,`friend`,`open`) VALUES (?, ?, '1')");
                $insertChat->bind_param("ii", $uid, $fid);
                $insertChat->execute();

                $friendData = $conn->prepare("SELECT * FROM `users` WHERE `id`=?");
                $friendData->bind_param("i", $fid);
                $friendData->execute();
                $friendRow = $friendData->get_result()->fetch_assoc();
                $friendName = $friendRow['username'];
                $friendAvatar = $friendRow['avatar'];

                $data = array(
                    "friend_name" => $friendName,
                    "friend_avatar" => $friendAvatar
                );
                echo json_encode($data);
            }
        }
    }
    if (isset($_POST['max_chat'])) { // Minimize chat
        $fid = $_POST['friend_id'];
        $action = $_POST['action'];
        $checkChat = $conn->query("SELECT * FROM `active_chats` WHERE `user`='$uid' AND `friend`='$fid'");
        if ($checkChat->num_rows == 1) {
            if ($action == "maximize") {
                $conn->query("UPDATE `active_chats` SET `open`= 1 WHERE `user`='$uid' AND `friend`='$fid'");
            } else
            if ($action == "minimize") {
                $conn->query("UPDATE `active_chats` SET `open`= 0 WHERE `user`='$uid' AND `friend`='$fid'");
            }
        }
    }
    if (isset($_POST['close_chat'])) { // Close chat
        $fid = $_POST['friend_id'];
        $checkChat = $conn->query("SELECT * FROM `active_chats` WHERE `user`='$uid' AND `friend`='$fid'");
        if ($checkChat->num_rows == 1) {
            $conn->query("DELETE FROM `active_chats` WHERE `user`='$uid' AND `friend`='$fid'");
        }
    }
    if (isset($_POST['load_chat'])) { // Load chat
        $friend = $_POST['friend_id'];
        $getMessages = $conn->query("SELECT * FROM `messages` WHERE `from`='$uid' AND `to`='$friend' OR `from`='$friend' AND `to`='$uid' ORDER BY `date` ASC");
        if ($getMessages->num_rows > 0) {
            while ($msgData = $getMessages->fetch_assoc()) {
                $msg = decrypt($msgData['content']);
                $msg_from = $msgData['from'];
                $msg_to = $msgData['to'];
                $msg_date = $msgData['date'];
                $msg_id = $msgData['id'];
                $msg_time = date("H:i", $msg_date);
                $msg_date = date("d.m.Y", $msg_date);
#
                if ($msg_from == $uid) {
                    $msg_class = " me";
                } else {
                    $msg_class = "";
                }

                $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$friend'");
                $friendData = $getFriendData->fetch_assoc();
                $friend_username = $friendData['username'];
                $friend_avatar = "../".$friendData['avatar'];
                if ($friend_avatar == "../") {
                    $friend_avatar = "../assets/images/logo_faded_clean.png";
                }
#
                if ($msg_from == $uid) {
                    $msg_from = "You";
                } else {
                    $msg_from = $friend_username;
                }
                ?>
                <div class="message<?=$msg_class?>">
                    <div class="message-user">
                        <?php if ($msg_class == "") {?>
                        <div class="message-user-avatar"><img src="<?=$friend_avatar?>"></div>
                        <div class="message-user-name"><?=$msg_from?></div>
                        <?php } else {?>
                        <div class="message-user-name"><?=$msg_from?></div>
                        <div class="message-user-avatar"><img src="<?=$avatar?>"></div>
                        <?php }?>
                    </div>
                    <div class="message-content"><p><?=$msg?></p></div>
                </div>
                <?php
            }
        }
    }
    if (isset($_POST['get_chat_user'])) {
        $fid = $_POST['friend_id'];
        $userData = $conn->query("SELECT * FROM `users` WHERE `id`='$fid'");
        $userRow = $userData->fetch_assoc();
        $friendName = $userRow['username'];
        $friendAvatar = $userRow['avatar'];

        $data = array(
            "friend_name" => $friendName,
            "friend_avatar" => $friendAvatar
        );
        echo json_encode($data);
    }
}

# Update avatar
if (isset($_POST['update_avatar'])) {
    if (!isset($_FILES["avatar"]) || $_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
        createCookie("msg", "No file was uploaded or an error occurred.", "10", null);
        exit;
    }

    // Ensure base path starts at web root
    $base_path = $_SERVER['DOCUMENT_ROOT'];
    $target_dir = $base_path . "/uploads/avatars/$uid/";
    $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
    $web_target_file = "/uploads/avatars/$uid/" . uniqid() . '.' . $imageFileType;
    $target_file = $base_path . $web_target_file;
    $uploadOk = 1;

    // Check if image file is actually an image
    $check = @getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check === false) {
        createCookie("msg", "File is not an image.", "10", null);
        exit;
    }

    // Create directories if they don't exist
    $directories = [
        $base_path . "/uploads",
        $base_path . "/uploads/avatars", 
        $base_path . "/uploads/avatars/$uid"
    ];

    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0755, true)) {
                createCookie("msg", "Failed to create upload directory.", "10", null);
                exit;
            }
        }
    }

    // Check file size (4MB)
    if ($_FILES["avatar"]["size"] > 4194304) {
        createCookie("msg", "Sorry, your file is too large.", "10", null);
        exit;
    }

    // Allow certain file formats
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        createCookie("msg", "Sorry, only JPG, JPEG, PNG & GIF files are allowed.", "10", null);
        exit;
    }

    // Try to upload file using absolute path
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        // Store relative web path in database
        $stmt = $conn->prepare("UPDATE `users` SET `avatar` = ? WHERE `id` = ?");
        $stmt->bind_param("si", $web_target_file, $uid);
        
        if (!$stmt->execute()) {
            createCookie("msg", "Failed to update database.", "10", null);
            unlink($target_file); // Remove uploaded file if db update fails
            exit;
        }
        
        createCookie("msg", "Avatar updated successfully.", "10", null);
    } else {
        createCookie("msg", "Sorry, there was an error uploading your file.", "10", null);
    }
}

# Update wallpaper
if (isset($_POST['update_wallpaper'])) {
    if (!isset($_FILES["wallpaper"]) || $_FILES["wallpaper"]["error"] !== UPLOAD_ERR_OK) {
        createCookie("msg", "No file was uploaded or an error occurred.", "10", null);
        exit;
    }

    // Ensure base path starts at web root 
    $base_path = $_SERVER['DOCUMENT_ROOT'];
    $target_dir = $base_path . "/uploads/wallpapers/$uid/";
    $imageFileType = strtolower(pathinfo($_FILES["wallpaper"]["name"], PATHINFO_EXTENSION));
    $web_target_file = "/uploads/wallpapers/$uid/" . uniqid() . '.' . $imageFileType;
    $target_file = $base_path . $web_target_file;
    $uploadOk = 1;

    // Check if image file is actually an image
    $check = @getimagesize($_FILES["wallpaper"]["tmp_name"]);
    if ($check === false) {
        createCookie("msg", "File is not an image.", "10", null);
        exit;
    }

    // Create directories if they don't exist
    $directories = [
        $base_path . "/uploads",
        $base_path . "/uploads/wallpapers",
        $base_path . "/uploads/wallpapers/$uid"
    ];

    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0755, true)) {
                createCookie("msg", "Failed to create upload directory.", "10", null);
                exit;
            }
        }
    }

    // Check file size (20MB)
    if ($_FILES["wallpaper"]["size"] > 20971520) {
        createCookie("msg", "Sorry, your file is too large.", "10", null);
        exit;
    }

    // Allow certain file formats
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        createCookie("msg", "Sorry, only JPG, JPEG, PNG & GIF files are allowed.", "10", null);
        exit;
    }

    // Try to upload file using absolute path
    if (move_uploaded_file($_FILES["wallpaper"]["tmp_name"], $target_file)) {
        // Store relative web path in database
        $stmt = $conn->prepare("UPDATE `users` SET `wallpaper` = ? WHERE `id` = ?");
        $stmt->bind_param("si", $web_target_file, $uid);
        
        if (!$stmt->execute()) {
            createCookie("msg", "Failed to update database.", "10", null);
            unlink($target_file); // Remove uploaded file if db update fails
            exit;
        }
        
        createCookie("msg", "Wallpaper updated successfully.", "10", null);
    } else {
        createCookie("msg", "Sorry, there was an error uploading your file.", "10", null);
    }
}

if (isset($_POST['update_account'])) {
    $newemail = $_POST['email'];
    $newusername = $_POST['username'];
    $dob = $_POST['dob'];
    
    if ($newemail != $email) {
        $checkEmail = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $checkEmail->bind_param("s", $newemail);
        $checkEmail->execute();
        $result = $checkEmail->get_result();
        if ($result->num_rows == 0) {
            $newemail = encrypt($newemail); // Encrypt the new email
            $updateEmail = $conn->prepare("UPDATE `users` SET `email` = ? WHERE `id` = ?");
            $updateEmail->bind_param("si", $newemail, $uid);
            $updateEmail->execute();
        }
        $checkEmail->close();
    }
    if ($newusername != $username) {
        $checkUsername = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $checkUsername->bind_param("s", $newusername);
        $checkUsername->execute();
        $result = $checkUsername->get_result();
        if ($result->num_rows == 0) {
            $updateUsername = $conn->prepare("UPDATE `users` SET `username` = ? WHERE `id` = ?");
            $updateUsername->bind_param("si", $newusername, $uid);
            $updateUsername->execute();
        }
        $checkUsername->close();
    }
    if (!empty($dob)) {
        if ($rank > 0) {
            $updateDob = $conn->prepare("UPDATE `users` SET `birth_date` = ? WHERE `id` = ?");
            $updateDob->bind_param("si", $dob, $uid);
            $updateDob->execute();
        }
    }

    header("location: ../settings");
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
    // Sanitize input data
    $text = $conn->real_escape_string($_POST['text']);
    $created = date('Y-m-d H:i:s');
    $uid = $_SESSION['uid'];
    
    // Insert post data using prepared statements
    $stmt = $conn->prepare("INSERT INTO `posts` (`user`, `content`, `created`) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $uid, $text, $created);
    $stmt->execute();
    
    // Retrieve the last inserted post
    $result = $conn->query("SELECT * FROM `posts` WHERE `user`='$uid' AND `created`='$created'");
    $postData = $result->fetch_assoc();
    
    if ($postData) {
        $post_id = $postData['id'];
    
        // Prepare file upload
        $target_dir = "uploads/post/$uid/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    
        $target_file = $target_dir . uniqid() . '.' . $imageFileType;
    
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully.";
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Error inserting post data.";
    }

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
    ?><script>window.location.href = "../";</script><?php
}

# Delete post
if (isset($_POST['deletePost'])) {
    // Validate and sanitize the input
    $post = filter_var($_POST['post_id'], FILTER_VALIDATE_INT);
    
    if ($post !== false) {
        // Use prepared statements to prevent SQL injection
        $deletePostQuery = $conn->prepare("DELETE FROM `posts` WHERE `id` = ?");
        $deletePostQuery->bind_param("i", $post);
        
        $deleteCommentsQuery = $conn->prepare("DELETE FROM `comments` WHERE `post` = ?");
        $deleteCommentsQuery->bind_param("i", $post);
        
        // Execute the queries
        $deletePostQuery->execute();
        $deleteCommentsQuery->execute();
        
        // Check for successful deletion
        if ($deletePostQuery->affected_rows > 0 || $deleteCommentsQuery->affected_rows > 0) {
            // Deletion successful
        } else {
            // Handle deletion failure
        }
        
        // Close the prepared statements
        $deletePostQuery->close();
        $deleteCommentsQuery->close();
    } else {
        // Invalid input, handle accordingly
    }
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