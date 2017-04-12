<?php

require("config.php");
//sanitize post value
$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
$tags = json_decode($_POST["tags"]);

//throw HTTP error if page number is not valid
if (!is_numeric($page_number)) {
  header('HTTP/1.1 500 Invalid page number!');
  exit();
}

//get current starting point of records
$position = (($page_number - 1) * $item_per_page);

if (empty($tags)) {
  $results = $mysqli->prepare("SELECT id, score, url, title, thumbnail FROM Image ORDER BY score  DESC LIMIT ?, ?");

  $results->bind_param("dd", $position, $item_per_page);
  $results->execute();
  $results->bind_result($id, $score, $url, $title, $thumbnail);

} else {
  $tag_string = implode(",", $tags);

  $results = $mysqli->prepare("SELECT id, score, url, title, thumbnail FROM Image WHERE id IN ( SELECT image_id FROM Tagmap WHERE tag_id IN ($tag_string) GROUP BY image_id HAVING count(DISTINCT(tag_id)) = ? ) ORDER BY score DESC LIMIT ?, ?");

  $results->bind_param("ddd", count($tags), $position, $item_per_page);
  $results->execute();
  $results->bind_result($id, $score, $url, $title, $thumbnail);

}

while ($results->fetch()) {
  $exists = getimagesize($url);

  if (is_array($exists)) {
    list($width, $height) = $exists;
    //  echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
    echo '<div class="item" data-w="' . $width . '" data-h="' . $height . '">';
      echo '<a href="' . 'http://reddit.com/' . $id . '">';
        echo '<img ';
        echo 'src="' . $url . '" ';
        echo 'alt="' . $title . '" >';
      echo '</a>';
    echo '</div>';
  }
}

?>
