<?php
header('Content-Type: application/json');

if (isset($_GET['url'])) {
    $url = $_GET['url'];

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

    // Check if the URL is age-restricted
    $urlRestricted = false;
    foreach ($ageRestrictedUrls as $ageRestrictedUrl) {
        if (strpos($url, $ageRestrictedUrl) !== false) {
            $urlRestricted = true;
            break;
        }
    }

    echo json_encode([
        'title' => $title,
        'description' => $description,
        'featured_image' => $featured_image,
        'logo' => $logo,
        'restricted' => $urlRestricted,
    ]);
}
