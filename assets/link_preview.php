<?php include 'functions.php';

$urls = $_POST['urls'];

foreach ($urls as $url) {
    echo shortenUrlToDomain($url);
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
?>