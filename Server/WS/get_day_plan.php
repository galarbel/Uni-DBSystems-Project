<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_culinary_day (?, ?, ?, ?, ?, ?)";

$default_max_price = 4;
$default_night_person = 1;
$default_max_distance = 0.1;
$default_force_morning = 1;

$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price);
$night_person = getNumericParamOrDefault($_REQUEST, "night_person", false, $default_night_person);
$max_distance = getNumericParamOrDefault($_REQUEST, "distance", false, $default_max_distance);
$force_morning = getNumericParamOrDefault($_REQUEST, "force_morning", false, $default_force_morning);
$lati = getNumericParamOrDefault($_REQUEST, "latitude", true, null);
$longi = getNumericParamOrDefault($_REQUEST, "longitude", true, null);

$return = [];

$requestsParams = [$lati, $longi, $max_price,$night_person,$max_distance,$force_morning];
$results = $db->rawQuery($sqlQuery, $requestsParams);

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

header('Content-type: application/json');
echo json_encode($return);

?>