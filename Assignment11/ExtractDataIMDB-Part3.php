<?php
// Name: Deepak
// Date: 04/17/2024
include('simple_html_dom.php');
include('db_config.php'); 
function storeLinksInDatabase($conn, $links) {
    $stmt = $conn->prepare("INSERT INTO ImdbLinks (url) VALUES (?)");
    foreach ($links as $link) {
        $stmt->bind_param("s", $link);
        $stmt->execute();
    }
}
function extractDataFromIMDb($url) {
    $html = file_get_html($url);
    if (!$html) {
        return false;
    }
    $data = [];
    $data['title'] = $html->find('title', 0)->plaintext;
    $data['description'] = $html->find("meta[name=description]", 0)->content;
    $data['links'] = [];

    foreach ($html->find('a') as $link) {
        if (!empty($link->href)) {
            $data['links'][] = $link->href;
        }
    }
    $html->clear();
    unset($html);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $url = $_POST['search'];
    $data = extractDataFromIMDb($url);

    if ($data) {
        storeLinksInDatabase($conn, $data['links']);
        echo "<h1>Title: {$data['title']}</h1>";
        echo "<p>Description: {$data['description']}</p>";
        echo "<h2>All Links:</h2>";
        foreach ($data['links'] as $link) {
            echo "<p><a href='$link'>$link</a></p>";
        }
    } else {
        echo "Error: Unable to access IMDb page or data not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>IMDb Data Extractor</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search">IMDb URL:</label>
        <input type="text" id="search" name="search">
        <input type="submit" value="Extract Data">
    </form>
</body>
</html>
