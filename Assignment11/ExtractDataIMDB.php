<?php
// Name: Deepak
// Date: 04/17/2024
include('simple_html_dom.php');
$url = 'https://www.imdb.com/title/tt0898266/';
$html = file_get_html($url);
if(!$html) {
    die("Error: Unable to access IMDB page.");
}
$title = $html->find('title', 0)->plaintext;
echo "<h1>Title: $title</h1>";
$description = $html->find("meta[name=description]", 0)->content;
echo "<p>Description: $description</p>";
$keywords = $html->find("meta[name=keywords]", 0)->content;
echo "<p>Keywords: $keywords</p>";
echo "<h2>All Links:</h2>";
foreach($html->find('a') as $link) {
    echo "<p><a href='".$link->href."'>".$link->plaintext."</a></p>";
}
echo "<h2>All Images:</h2>";
foreach($html->find('img') as $image) {
    echo "<img src='".$image->src."' alt='".$image->alt."'><br>";
}
$ratingDivs = $html->find('div[data-testid="hero-rating-bar__aggregate-rating"]');
if ($ratingDivs) {
    foreach ($ratingDivs as $div) {
        if (strpos($div->innertext, 'IMDb RATING') !== false) {
            $ratingSpan = $div->find('span.sc-bde20123-1', 0);
            if ($ratingSpan) {
                $rating = trim($ratingSpan->plaintext);
                echo "<p>Rating: $rating</p>";
                break;
            }
        }
    }
} else {
    echo "<p>Rating not found</p>";
}
$html->clear();
unset($html);
?>
