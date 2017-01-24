<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_places_by_parameters (?, ?, ?, ?, ?)";

$default_city_id = null;
$default_category_id = null;
$default_max_price = 4;
$default_meal_id = null;
$default_minimum_rating = 6.0;

$city_id = getNumericParamOrDefault($_REQUEST, "city", false, $default_city_id);
$category_id = getNumericParamOrDefault($_REQUEST, "category", false, $default_category_id);
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price);
$meal_id = getNumericParamOrDefault($_REQUEST, "meal", false, $default_meal_id);
$minimum_rating = getNumericParamOrDefault($_REQUEST, "m_rating", false, $default_minimum_rating);

$requestsParams = [$city_id, $category_id, $max_price,$meal_id,$minimum_rating];
$results = $db->rawQuery($sqlQuery, $requestsParams);

for ($i = 0; $i < sizeof($results); $i++) {
    parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>