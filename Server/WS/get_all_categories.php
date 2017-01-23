<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT * FROM Categories ORDERY BY categoy_name ASC";

header('Content-type: application/json');
$results = $db->rawQuery($sqlQuery);

echo json_encode($results);

?>