<?php

include_once '../Global/config.php';

/** API for liking a review of a place
 *
 *  Method : POST
 *
 *  Required body properties:
 *  review_id - numeric
 *
 *  Returns:
 *  On success - Success (200) with no content
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */

header('Content-type: application/json');

$review_id = getNumericParamOrDefault($_REQUEST, "review_id", true, null);
$sqlQuery = "call web_add_like_to_review(?)";
$results = $db->rawQuery($sqlQuery, [$review_id]);

?>