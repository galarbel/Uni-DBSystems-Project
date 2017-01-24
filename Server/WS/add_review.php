<?php

include_once '../Global/config.php';

$post_body = json_decode(file_get_contents('php://input'), true);

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

$sqlQuery = "call web_insert_review (?, ?, ?, ?)";
$results = $db->rawQuery($sqlQuery, [$place_id, $first_name, $last_name, $review_text]);

?>

