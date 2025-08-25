<?php
// Check if the 'url' parameter is set in the query string
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    // Use file_get_contents to fetch the HTML content from the specified URL
    $html_content = file_get_contents($url);
    // Print the fetched content to the browser
    echo $html_content;
} else {
    // If no URL is provided, display an error message
    echo "<h1>Error: No URL specified.</h1>";
    echo "<p>Please provide a URL in the query string, like: ?url=https://example.com</p>";
}
?>
