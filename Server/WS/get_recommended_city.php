<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_buzz_city (?, ?)";

$default_max_price = null;

$category_id = getNumericParamOrDefault($_REQUEST, "categoryId", true, null); // mandatory
$max_price = getNumericParamOrDefault($_REQUEST, "price", false, $default_max_price); // optional

$requestsParams = [$category_id, $max_price];
$results = $db->rawQuery($sqlQuery, $requestsParams)[0];

header('Content-type: application/json');
echo json_encode($results);

?>