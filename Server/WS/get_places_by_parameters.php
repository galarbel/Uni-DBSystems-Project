<?php

include_once '../Global/config.php';

/** API for finding places that match the user input requirement.
 *
 *  Method : GET
 *
 *  Required parameters: none.
 *
 *  Optional parameters:
 *  city - numeric . default value: null.  Only suggest places in this city.
 *  category - numeric . default value: null. Only suggest places in this category.
 *  meal - numeric (1-4). default value: 4. Only suggest places suitable for this kind of meal.
 *  price - numeric (1-4). default value: 4. The maximum price level the user is willing to spend
 *  m_rating - numeric. default value: 6.0. Only suggest places with this minimum rating.
 *
 *  Returns:
 *  On success - Success (200) with list of places that matches the requirements
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */


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

// convert results to client compatible form.
for ($i = 0; $i < sizeof($results); $i++) {
    parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>