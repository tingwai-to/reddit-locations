<?php

require("config.php");

$results = $mysqli->prepare("SELECT * FROM Tag");
$results->execute();
$all_tags = $results->get_result();


$tag_array = array();
while ($row = $all_tags->fetch_array(MYSQLI_BOTH)) {
  $tag_array[$row['id']] = $row['name'];
}

?>
