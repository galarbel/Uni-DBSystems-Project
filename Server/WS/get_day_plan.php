<?php

include_once '../Global/config.php';

/** API for getting a suggested culinary day plan by input refinements
 *
 *  Method : GET
 *
 *  Required parameters:
     *  latitude - numeric
     *  latitude - numeric
 *
 *  Optional parameters:
    *  price - numeric (1-4). default value: 4. The maximum price level the user is willing to spend
    *  night_person - numeric (0 or 1). default value: 1. Whether or not to suggest a venue suitable for late hours
    *  distance - numeric. default value: 0.1. The maximum distance between suggested venues and the user's location
    *  force_morning - numeric (0 or 1). default value: 1. Whether or not to suggest a breakfast venue even if the user is a night person.
 *
 *  Returns:
     *  On success - Success (200) with the day plan array that contains at most 4 venues, and a subset of their related data.
     *  On invalid params - Bad Request (400) with an error message
     *  On DB error - Internal Server Error (500) with an error message
 */


$sqlQuery = "call web_get_culinary_day (?, ?, ?, ?, ?, ?)";

$default_max_price = 4;
$default_night_person = 1;
$default_max_distance = 0.1;
$default_force_morning = 1;

// validate and set params for the query
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price);
$night_person = getNumericParamOrDefault($_REQUEST, "night_person", false, $default_night_person);
$max_distance = getNumericParamOrDefault($_REQUEST, "distance", false, $default_max_distance);
$force_morning = getNumericParamOrDefault($_REQUEST, "force_morning", false, $default_force_morning);
$lati = getNumericParamOrDefault($_REQUEST, "latitude", true, null);
$longi = getNumericParamOrDefault($_REQUEST, "longitude", true, null);

// execute query that calls our 'web_get_culinary_day' stored procedure
$requestsParams = [$lati, $longi, $max_price,$night_person,$max_distance,$force_morning];
$results = $db->rawQuery($sqlQuery, $requestsParams);

// build the response
$return = [];
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