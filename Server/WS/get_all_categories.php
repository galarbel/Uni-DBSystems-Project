<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT * FROM Categories";

header('Content-type: application/json');
$results = $db->rawQuery($sqlQuery);

echo json_encode($results);

?>