<?php

include_once '../Global/config.php';

$sqlQuery = "call server_get_by_distance (?, ?, ?)";

$lati = null;
$longi = null;
$max_price = null;


if (isset($_REQUEST["latitude"]) && is_numeric($_REQUEST["latitude"])) {
    $lati = $_REQUEST["latitude"];
}

if (isset($_REQUEST["longitude"]) && is_numeric($_REQUEST["longitude"])) {
    $longi = $_REQUEST["longitude"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (! ($lati && $longi)) {
    die;
}


/* OK - print JSON */

header('Content-type: application/json');

$return = [];


$requestsParams = [$lati, $longi, $max_price];
$results = $db->rawQuery($sqlQuery, $requestsParams);

//echo json_encode($results); die;

foreach ($results as $p) {
    switch ($p["category_type"]) {
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