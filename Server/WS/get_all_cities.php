<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT city_id, city_name FROM Cities ";

header('Content-type: application/json');
$results = $db->rawQuery($sqlQuery);

echo json_encode($results);

?>