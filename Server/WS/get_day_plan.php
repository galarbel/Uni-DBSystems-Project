<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_culinary_day (?, ?, ?, ?, ?, ?)";

$max_price = 4;
$night_person = 1;
$max_distance = 0.1;
$force_morning = 1;

if (!isset($_REQUEST["latitude"]) || !is_numeric($_REQUEST["latitude"])) {
    badRequest("missing 'latitude' parameter or not numeric");
}

if (!isset($_REQUEST["longitude"]) || !is_numeric($_REQUEST["longitude"])) {
    badRequest("missing 'longitude' parameter or not numeric");
}

if (isset($_REQUEST["price"])) {
    if (!is_numeric($_REQUEST["price"])) {
        badRequest("'price' parameter is not numeric");
    } else {
        $max_price = $_REQUEST["price"];
    }
}

if (isset($_REQUEST["night_person"])) {
    if (!is_numeric($_REQUEST["night_person"])) {
        badRequest("'night_person' parameter is not numeric");
    } else {
        $night_person = $_REQUEST["night_person"];
    }
}

if (isset($_REQUEST["force_morning"])) {
    if (!is_numeric($_REQUEST["force_morning"])) {
        badRequest("'force_morning' parameter is not numeric");
    } else {
        $force_morning = $_REQUEST["force_morning"];
    }
}

if (isset($_REQUEST["distance"])) {
    if (!is_numeric($_REQUEST["distance"])) {
        badRequest("'distance' parameter is not numeric");
    } else {
        $max_distance = $_REQUEST["distance"];
    }
}


/* OK - print JSON */

header('Content-type: application/json');

$return = [];

$lati = $_REQUEST["latitude"];
$longi = $_REQUEST["longitude"];


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