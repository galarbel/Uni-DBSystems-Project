<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["name"])) {
    echo 'missing \'name\' parameter'; die;
}

header('Content-type: application/json');
$name = "'+" . $_REQUEST["name"] . "*'";

// TODO - decide on relevant data
$sqlQuery = "SELECT place_id, name, address, city_name, state, rating
             FROM v_places
             WHERE match (name) against (? in boolean mode) limit 5";
$results = $db->rawQuery($sqlQuery, [$name]);

echo json_encode($results);

?>