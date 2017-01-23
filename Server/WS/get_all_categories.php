<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT * FROM Categories ORDER BY category_name ASC";

header('Content-type: application/json');
$results = $db->rawQuery($sqlQuery);

echo json_encode($results);

?>