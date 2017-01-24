<?php

include_once '../Global/config.php';

/** API for getting the possible categories of venues
 *
 *  Method : GET
 *
 *  Required/optional parameters: none.
 *
 *  Returns:
 *  On success - Success (200) with a list of categories (ID's and names)
 *  On DB error - Internal Server Error (500) with an error message
 */

$sqlQuery = "SELECT * FROM Categories ORDER BY category_name ASC";

$results = $db->rawQuery($sqlQuery);

header('Content-type: application/json');
echo json_encode($results);

?>