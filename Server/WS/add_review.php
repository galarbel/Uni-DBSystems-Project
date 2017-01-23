<?php

include_once '../Global/config.php';

$post_body = json_decode(file_get_contents('php://input'), true);

if (!isset($post_body["place_id"]) || !is_numeric($post_body["place_id"])) {
    badRequest("missing 'place_id' parameter or not numeric");
}
if (!isset($post_body["review_text"])) {
    badRequest("missing 'review_text'");
}

if (!isset($post_body["first_name"])) {
    badRequest("missing 'first_name'");
}

header('Content-type: application/json');

$place_id = $post_body["place_id"];
$review_text = $post_body["review_text"];
$first_name = $post_body["first_name"];
$last_name = isset($post_body["last_name"]) ? $post_body["last_name"] : null; // last name is optional


$sqlQuery = "call web_insert_review (?, ?, ?, ?)";
$results = $db->rawQuery($sqlQuery, [$place_id, $first_name, $last_name, $review_text]);

?>

