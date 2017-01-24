<?php

include_once '../Global/config.php';

/** API for adding a review to a specific place
 *
 *  Method : POST
 *
 *  Required body properties:
 *  place_id - numeric
 *  review_text
 *  first_name
 *
 *  Optional body properties:
 *  last_name, default value: null
 *
 *  Returns:
 *  On success - Success (200) with no content
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */

// get POST body
$post_body = json_decode(file_get_contents('php://input'), true);

// check params
if (!isset($post_body["review_text"])) {
    badRequest("missing 'review_text' parameter");
}
if (!isset($post_body["first_name"])) {
    badRequest("missing 'first_name' parameter");
}
$place_id = getNumericParamOrDefault($post_body, "place_id", true, null);
$review_text = $post_body["review_text"];
$first_name = $post_body["first_name"];
$last_name = isset($post_body["last_name"]) ? $post_body["last_name"] : null; // last name is optional


header('Content-type: application/json');

// call web_insert_review stored procedure
$sqlQuery = "call web_insert_review (?, ?, ?, ?)";
$results = $db->rawQuery($sqlQuery, [$place_id, $first_name, $last_name, $review_text]);

?>

