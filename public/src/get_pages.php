<?php

require("config.php");

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
  $results = $mysqli->prepare("SELECT id, score, url, title, thumbnail, preview FROM Image ORDER BY created_utc  DESC LIMIT ?, ?");

  $results->bind_param("dd", $position, $item_per_page);
  $results->execute();
  $results->bind_result($id, $score, $url, $title, $thumbnail, $preview);

} else {
  $tag_string = implode(",", $tags);

  $results = $mysqli->prepare("SELECT id, score, url, title, thumbnail, preview FROM Image WHERE id IN ( SELECT image_id FROM Tagmap WHERE tag_id IN ($tag_string) GROUP BY image_id HAVING count(DISTINCT(tag_id)) = ? ) ORDER BY created_utc DESC LIMIT ?, ?");

  $results->bind_param("ddd", count($tags), $position, $item_per_page);
  $results->execute();
  $results->bind_result($id, $score, $url, $title, $thumbnail, $preview);

}

while ($results->fetch()) {
  if (is_null($preview)) {
    $exists = getimagesize($url);

    if (is_array($exists)) {
      list($width, $height) = $exists;
      echo "<div class='item' data-w='$width' data-h='$height'>";
      echo "<a href='$url' data-lightbox='$id' 
            data-title='<a href=\"http://reddit.com/$id\" target=\"_blank\">" .
        htmlspecialchars($title, ENT_QUOTES, 'UTF-8') .
        "</a>'>";
      echo "<img src='$url' alt='$title'>";
      echo "</a>";
      echo "</div>";
    }
  } else {
    $exists = getimagesize($preview);

    if (is_array($exists)) {
      list($width, $height) = $exists;
      echo "<div class='item' data-w='$width' data-h='$height'>";
      echo "<a href='$url' data-lightbox='$id' 
            data-title='<a href=\"http://reddit.com/$id\" target=\"_blank\">" .
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8') .
            "</a>'>";
      echo "<img src='$preview' alt='$title'>";
      echo "</a>";
      echo "</div>";
    }
  }
}

?>
