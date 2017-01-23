<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_culinary_day (?, ?, ?, ?, ?, ?)";

$lati = 40.759082; /*Times Square , NY */
$longi = -73.985088;
$max_price = 4;
$night_person = 1;
$max_distance = 0.1;
$force_morning = 1;

if (isset($_REQUEST["latitude"]) && is_numeric($_REQUEST["latitude"])) {
    $lati = $_REQUEST["latitude"];
}

if (isset($_REQUEST["longitude"]) && is_numeric($_REQUEST["longitude"])) {
    $longi = $_REQUEST["longitude"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (isset($_REQUEST["night_person"]) && is_numeric($_REQUEST["night_person"])) {
    $night_person = $_REQUEST["night_person"];
}

if (isset($_REQUEST["distance"]) && is_numeric($_REQUEST["distance"])) {
    $max_distance = $_REQUEST["distance"];
}

if (isset($_REQUEST["force_morning"]) && is_numeric($_REQUEST["force_morning"])) {
    $force_morning = $_REQUEST["force_morning"];
}

if (! ($lati && $longi && $max_price && $max_distance)) {
    echo "something went wrong";
    die;
}


/* OK - print JSON */

header('Content-type: application/json');

$return = [];


$requestsParams = [$lati, $longi, $max_price,$night_person,$max_distance,$force_morning];
$results = $db->rawQuery($sqlQuery, $requestsParams);

//echo json_encode($results); die;

foreach ($results as $p) {
    parsePlaceCategories($p);
    parsePlacePhoto($p);
    switch ($p["meal_id"]) {
        case 1:
            $return["morning"] = $p;
            break;
        case 2:
            $return["lunch"] = $p;
            break;
        case 3:
            $return["dinner"] = $p;
            break;
        case 4:
            $return["night"] = $p;
            break;
    }
}

echo json_encode($return);

?>