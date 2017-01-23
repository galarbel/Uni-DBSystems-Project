<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_replace_options (?, ?, ?, ?, ?)";

$default_max_price = 4;
$default_meal = 2;
$default_max_distance = 0.1;

$lati = getNumericParamOrDefault($_REQUEST, "latitude", true, null);
$longi = getNumericParamOrDefault($_REQUEST, "longitude", true, null);
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price);
$meal = getNumericParamOrDefault($_REQUEST, "meal", false, $default_meal);
$max_distance = getNumericParamOrDefault($_REQUEST, "meal", false, $default_max_distance);


$requestsParams = [$lati, $longi, $meal, $max_price, $max_distance];
$results = $db->rawQuery($sqlQuery, $requestsParams);

for ($i = 0; $i < sizeof($results); $i++) {
	parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>