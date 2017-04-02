<?php

require("config.php");
//sanitize post value
$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

//throw HTTP error if page number is not valid
if (!is_numeric($page_number)) {
  header('HTTP/1.1 500 Invalid page number!');
  exit();
}

//get current starting point of records
$position = (($page_number - 1) * $item_per_page);

//fetch records using page position and item per page. 
$results = $mysqli->prepare("SELECT id, score, url, title, thumbnail FROM Image ORDER BY score DESC LIMIT ?, ?");

//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
$results->bind_param("dd", $position, $item_per_page);
$results->execute(); //Execute prepared Query
$results->bind_result($id, $score, $url, $title, $thumbnail); //bind variables to prepared statement

while ($results->fetch()) {
  echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 portfolio-item">';
    echo '<a href="' . 'http://reddit.com/' . $id . '">';
      echo '<img class="img-thumbnail"';
           echo 'src="' . $url . '"';
           echo 'alt="">';
    echo '</a>';
  echo '</div>';
}

?>
