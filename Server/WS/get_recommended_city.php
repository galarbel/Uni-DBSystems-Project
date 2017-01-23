<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_buzz_city_by_category (?, ?)";

$category_id = null;
$max_price = null;

if (isset($_REQUEST["categoryId"]) && is_numeric($_REQUEST["categoryId"])) {
    $category_id = $_REQUEST["categoryId"];
}

if (isset($_REQUEST["price"]) && is_numeric($_REQUEST["price"])) {
    $max_price = $_REQUEST["price"];
}

if (!$category_id) {
    die;
}


/* OK - print JSON */

header('Content-type: application/json');

$requestsParams = [$category_id, $max_price];
$results = $db->rawQuery($sqlQuery, $requestsParams);


echo json_encode($results);

?>