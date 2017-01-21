<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_places_by_parameters (?, ?, ?, ?, ?)";

$city_id = 1300;
$category_id = null;
$max_price = 4;
$meal_id = null;
$minimum_rating = 6.0;

if (isset($_REQUEST["city"]) && is_numeric($_REQUEST["city"])) {
    $city_id = $_REQUEST["city"];
}

if (isset($_REQUEST["category"]) && is_numeric($_REQUEST["category"])) {
    $category_id = $_REQUEST["category"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (isset($_REQUEST["meal"]) && is_numeric($_REQUEST["meal"])) {
    $meal_id = $_REQUEST["meal"];
}

if (isset($_REQUEST["m_rating"]) && is_numeric($_REQUEST["m_rating"])) {
    $minimum_rating = $_REQUEST["m_rating"];
}


/* OK - print JSON */

header('Content-type: application/json');

$return = [];


$requestsParams = [$city_id, $category_id, $max_price,$meal_id,$minimum_rating];
$results = $db->rawQuery($sqlQuery, $requestsParams);

//echo json_encode($results); die;
for ($i = 0; $i < sizeof($results); $i++) {
    parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

echo json_encode($results);

?>