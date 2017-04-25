<?php

require("config.php");

//$results = $mysqli->prepare("SELECT * FROM Tag ORDER BY id DESC");
$results = $mysqli->prepare("SELECT id, name, count FROM ( SELECT tag_id, COUNT(*) AS count FROM Tagmap GROUP BY tag_id ) t1 JOIN ( SELECT * FROM Tag ) t2 ON t1.tag_id=t2.id ORDER BY count DESC");
$results->execute();
$all_tags = $results->get_result();

$num_of_rows = $all_tags->num_rows;

while ($row = $all_tags->fetch_assoc()) {
  echo "<option value='". $row['id'] . "'>" . $row['name'] . "</option>";
}

?>
