<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT place_id, foursquare_id FROM Places limit 5";
$results = $db->rawQuery($sqlQuery);

foreach ($results as $v) {
    $id = $v["place_id"];
    $f_id = $v["foursquare_id"];

    echo "id: " . $id . ", foursquare_id: " . $f_id ;
    echo "<br>";
}

?>