<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_clean();
header('Content-Type: application/json');

// Check if cURL is available
if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'cURL is not enabled on the server.']);
    exit;
}

// Validate input
if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

$url = $_GET['url'];

// List of restricted domains
$ageRestrictedUrls = [
    'pornhub.com', 'xvideos.com', 'xhamster.com', 'redtube.com', 'youporn.com',
    'xnxx.com', 'brazzers.com', 'chaturbate.com', 'livejasmin.com', 'myfreecams.com',
    'camsoda.com', 'stripchat.com', 'bongacams.com', 'cam4.com', 'flirt4free.com',
    'imlive.com', 'streamate.com', 'manyvids.com', 'onlyfans.com', 'justfor.fans',
    'fanpage.com', 'fansly.com', 'loyalfans.com', 'seegore.com', 'documentingreality.com'
];

// Check for restricted domains
$urlRestricted = false;
foreach ($ageRestrictedUrls as $restricted) {
    if (stripos($url, $restricted) !== false) {
        $urlRestricted = true;
        break;
    }
}

// Fetch HTML using cURL (with detailed error reporting)
function fetchHtml($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122.0.0.0 Safari/537.36',
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, // Bypass SSL validation (local/dev only)
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "cURL error: $error"];
    }

    curl_close($ch);
    return $response;
}

$html = fetchHtml($url);

// If fetch failed, return detailed error
if (is_array($html) && isset($html['error'])) {
    echo json_encode(['error' => $html['error'], 'restricted' => $urlRestricted]);
    exit;
}

if (!$html) {
    echo json_encode(['error' => 'Unknown error during URL fetch', 'restricted' => $urlRestricted]);
    exit;
}

// Parse HTML using DOM
libxml_use_internal_errors(true);
$doc = new DOMDocument();
$doc->loadHTML($html);
$xpath = new DOMXPath($doc);

// Extract title
$titleNode = $xpath->query('//title')->item(0);
$title = $titleNode ? trim($titleNode->textContent) : '';

// Extract meta description
$descNode = $xpath->query('//meta[@name="description"]')->item(0);
$description = $descNode ? $descNode->getAttribute('content') : '';

// Extract Open Graph image
$ogImageNode = $xpath->query('//meta[@property="og:image"]')->item(0);
$featured_image = $ogImageNode ? $ogImageNode->getAttribute('content') : '';

// Extract site icon
$iconNode = $xpath->query('//link[@rel="icon"]')->item(0);
$logo = $iconNode ? $iconNode->getAttribute('href') : '';

// Handle relative logo URLs
if ($logo && parse_url($logo, PHP_URL_SCHEME) === null) {
    $base = parse_url($url);
    $host = $base['scheme'] . '://' . $base['host'];
    $logo = $host . '/' . ltrim($logo, '/');
}

// Return preview data
echo json_encode([
    'title' => $title,
    'description' => $description,
    'featured_image' => $featured_image,
    'logo' => $logo,
    'restricted' => $urlRestricted
]);
