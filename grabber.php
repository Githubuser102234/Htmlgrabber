<?php
// Check if the 'url' parameter is set in the URL query string
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    // Use file_get_contents to fetch the HTML content from the specified URL
    // The @ suppresses any warnings or errors that might occur if the URL is invalid or inaccessible
    $html_content = @file_get_contents($url);

    if ($html_content !== false) {
        // If the content was fetched successfully, print it to the browser
        echo $html_content;
    } else {
        // Handle cases where the URL is invalid or content could not be fetched
        echo "<h1>Error: Could not retrieve content from the URL.</h1>";
        echo "<p>Please ensure the URL is valid and accessible.</p>";
    }
} else {
    // If no URL is provided, display an error message
    echo "<h1>Error: No URL specified.</h1>";
    echo "<p>Please return to the previous page and provide a URL.</p>";
}
?>
