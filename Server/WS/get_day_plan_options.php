<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_replace_options (?, ?, ?, ?, ?)";

$lati = 40.759082; /*Times Square , NY */
$longi = -73.985088;
$max_price = 4;
$meal = 2;
$max_distance = 0.1;

if (isset($_REQUEST["latitude"]) && is_numeric($_REQUEST["latitude"])) {
    $lati = $_REQUEST["latitude"];
}

if (isset($_REQUEST["longitude"]) && is_numeric($_REQUEST["longitude"])) {
    $longi = $_REQUEST["longitude"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (isset($_REQUEST["meal"]) && is_numeric($_REQUEST["meal"])) {
    $meal = $_REQUEST["meal"];
}

if (isset($_REQUEST["distance"]) && is_numeric($_REQUEST["distance"])) {
    $max_distance = $_REQUEST["distance"];
}

if (! ($lati && $longi && $max_price && $max_distance && $meal)) {
	echo "something went wrong";
    die;
}

/* OK - print JSON */

header('Content-type: application/json');

$requestsParams = [$lati, $longi, $meal, $max_price, $max_distance];
$results = $db->rawQuery($sqlQuery, $requestsParams);

//echo json_encode($results); die;
//addCategoriesToPlace($results[1]);


for ($i = 0; $i < sizeof($results); $i++) {
	parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

echo json_encode($results);

?>