<?php

include_once '../Global/config.php';

/** API for getting a possible replacements for one of the suggested meals in a culinary day plan.
 *
 *  Method : GET
 *
 *  Required parameters:
    *  latitude - numeric
    *  latitude - numeric
    *  meal - numeric (1-4). The meal to find replacements for (breakfast, dinner...)
 *
 *  Optional parameters:
    *  price - numeric (1-4). default value: 4. The maximum price level the user is willing to spend
    *  distance - numeric. default value: 0.1. The maximum distance between suggested venues and the user's location
 *
 *  Returns:
    *  On success - Success (200) with list of places that matches the parameters
    *  On invalid params - Bad Request (400) with an error message
    *  On DB error - Internal Server Error (500) with an error message
 */


$sqlQuery = "call web_get_replace_options (?, ?, ?, ?, ?)";

$default_max_price = 4;
$default_max_distance = 0.1;

$lati = getNumericParamOrDefault($_REQUEST, "latitude", true, null);
$longi = getNumericParamOrDefault($_REQUEST, "longitude", true, null);
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price);
$meal = getNumericParamOrDefault($_REQUEST, "meal", true, null);
$max_distance = getNumericParamOrDefault($_REQUEST, "distance", false, $default_max_distance);


$requestsParams = [$lati, $longi, $meal, $max_price, $max_distance];
$results = $db->rawQuery($sqlQuery, $requestsParams);

// convert results to client compatible form.
for ($i = 0; $i < sizeof($results); $i++) {
	parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>























