<?php

include_once '../Global/config.php';

header('Content-type: application/json');

$sqlQuery = "SELECT place_id, foursquare_id FROM Places limit 5";
$results = $db->rawQuery($sqlQuery);

echo json_encode($results);

?>