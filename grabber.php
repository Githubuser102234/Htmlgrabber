<<?php

// Check if a URL is provided
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    // Validate the URL to ensure it's a valid web address
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        die("Error: Invalid URL format.");
    }

    // Attempt to fetch the HTML content from the URL
    $html_content = @file_get_contents($url);

    if ($html_content === false) {
        die("Error: Could not retrieve content from the specified URL. Please check the URL and try again.");
    }

    // Create a new DOMDocument object
    $dom = new DOMDocument();

    // Suppress warnings from the parser for malformed HTML
    libxml_use_internal_errors(true);
    // Load the HTML content into the DOM object
    $dom->loadHTML($html_content);
    libxml_clear_errors();

    // Get the base URL of the fetched page for resolving relative links
    $parsed_url = parse_url($url);
    $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    if (isset($parsed_url['port'])) {
        $base_url .= ':' . $parsed_url['port'];
    }

    // Find and modify all elements with src or href attributes to make them absolute
    $elements = $dom->getElementsByTagName('*');
    foreach ($elements as $element) {
        if ($element->hasAttribute('src')) {
            $src = $element->getAttribute('src');
            if (strpos($src, '/') === 0) {
                $element->setAttribute('src', $base_url . $src);
            }
        }
        if ($element->hasAttribute('href')) {
            $href = $element->getAttribute('href');
            if (strpos($href, '/') === 0 && strpos($href, '//') !== 0) {
                $element->setAttribute('href', $base_url . $href);
            }
        }
    }

    // Output the modified HTML
    echo $dom->saveHTML();

} else {
    // If no URL is provided, display an error message
    echo "<h1>Error: No URL specified.</h1>";
    echo "<p>Please provide a URL in the query string, like: ?url=https://example.com</p>";
}

?>
