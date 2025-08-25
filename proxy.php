<?php
// Check for the 'url' parameter
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    // Use cURL for a more robust fetching method
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // Set a user-agent to mimic a real browser, which can prevent blocking
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    // Execute the request and get the content
    $html_content = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check if the request was successful
    if ($http_status == 200 && $html_content !== false) {
        // Find and modify all relative URLs to be absolute
        $parsed_url = parse_url($url);
        $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html_content, LIBXML_NOERROR);
        libxml_clear_errors();

        $tags = ['img', 'script', 'link', 'a'];
        foreach ($tags as $tag) {
            $elements = $dom->getElementsByTagName($tag);
            foreach ($elements as $element) {
                $attribute = ($tag == 'link' || $tag == 'a') ? 'href' : 'src';
                if ($element->hasAttribute($attribute)) {
                    $original_url = $element->getAttribute($attribute);
                    if (strpos($original_url, '/') === 0 && strpos($original_url, '//') !== 0) {
                        $element->setAttribute($attribute, $base_url . $original_url);
                    }
                }
            }
        }

        // Output the modified HTML
        echo $dom->saveHTML();
    } else {
        // Send a 400 Bad Request header if the fetch failed
        header("HTTP/1.1 400 Bad Request");
        echo "Error: Failed to fetch content from the URL. Status Code: " . $http_status;
    }
} else {
    // Send a 400 Bad Request header if no URL was provided
    header("HTTP/1.1 400 Bad Request");
    echo "Error: No URL specified.";
}
?>
