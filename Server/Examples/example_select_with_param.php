<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT place_id, foursquare_id FROM Places limit 5";
$results = $db->rawQuery($sqlQuery);

if (!isset($_REQUEST["name"])) {
    echo 'please put "?name=NAME" at the end of the url'; die;
}


header('Content-type: application/json');
$name = '%' . $_REQUEST["name"] . '%';

$sqlQuery = "SELECT place_id, foursquare_id, name FROM Places WHERE name like ? limit 5";
$results = $db->rawQuery($sqlQuery, [$name]);

echo json_encode($results);

?>