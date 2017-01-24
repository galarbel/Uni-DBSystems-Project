<?php

include_once '../Global/config.php';

/** API for finding a city with the top buzz score in a particular category.
 *
 *  Method : GET
 *
 *  Required parameters:
 *  categoryId = numeric. The user's desired venues category.
 *
 *  Optional parameters:
 *  price - numeric (1-4). default value: 4. The maximum price level the user is willing to spend
 *
 *  Returns:
 *  On success - Success (200) with the 'best city in a category' according to our algorithm for computing a buzz score.
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */


$sqlQuery = "call web_get_buzz_city (?, ?)";

$default_max_price = null;

$category_id = getNumericParamOrDefault($_REQUEST, "categoryId", true, null); // mandatory
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price); // optional

$requestsParams = [$category_id, $max_price];
$results = $db->rawQuery($sqlQuery, $requestsParams)[0];

header('Content-type: application/json');
echo json_encode($results);

?>