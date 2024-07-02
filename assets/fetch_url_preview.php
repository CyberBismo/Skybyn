<?php
function get_meta_tags_content($url) {
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

$urls = isset($_GET['urls']) ? explode(',', $_GET['urls']) : [];
$metadata_list = [];

foreach ($urls as $url) {
    $metadata_list[] = get_meta_tags_content(trim($url));
}

echo json_encode($metadata_list);
?>
