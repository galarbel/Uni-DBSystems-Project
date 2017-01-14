<?php

include_once '../Global/config.php';

$sqlQuery = "call server_get_by_distance_and_type (?, ?, ?,?)";

$lati = null;
$longi = null;
$type = null;
$max_price = null;


if (isset($_REQUEST["latitude"]) && is_numeric($_REQUEST["latitude"])) {
    $lati = $_REQUEST["latitude"];
}

if (isset($_REQUEST["longitude"]) && is_numeric($_REQUEST["longitude"])) {
    $longi = $_REQUEST["longitude"];
}

if (isset($_REQUEST["type"]) && is_numeric($_REQUEST["type"])) {
    $type = $_REQUEST["type"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (! ($lati && $longi && $type)) {
    die;
}


/* OK - print JSON */

header('Content-type: application/json');

$requestsParams = [$lati, $longi, $type, $max_price];
$results = $db->rawQuery($sqlQuery, $requestsParams);

//echo json_encode($results); die;
//addCategoriesToPlace($results[1]);


for ($i = 0; $i < sizeof($results); $i++) {
    addCategoriesToPlace($results[$i]);
    parsePlacePhoto($results[$i]);
}

echo json_encode($results);

?>