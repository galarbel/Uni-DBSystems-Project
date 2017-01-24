<?php

include_once '../Global/config.php';

/** API for getting all the cities in our database
 *
 *  Method : GET
 *
 *  Required/optional parameters: none.
 *
 *  Returns:
     *  On success - Success (200) with a list of cities (ID's and names)
     *  On DB error - Internal Server Error (500) with an error message
 */

$sqlQuery = "SELECT city_id, city_name FROM Cities ";
$results = $db->rawQuery($sqlQuery);

header('Content-type: application/json');
echo json_encode($results);

?>