<?php

include_once '../Global/config.php';

$post_body = json_decode(file_get_contents('php://input'), true);

if (!isset($_REQUEST["place_id"])) {
    http_response_code(400);
    echo 'missing \'place_id\' parameter'; die;
}
if (!isset($post_body["review_text"])) {
    http_response_code(400);
    echo 'missing \'review_text\''; die;
}
if (!isset($post_body["first_name"])) {
    http_response_code(400);
    echo 'missing \'first_name\''; die;
}

header('Content-type: application/json');

$place_id = $_REQUEST["place_id"];
$review_text = $post_body["review_text"];
$first_name = $post_body["first_name"];
$last_name = isset($post_body["last_name"]) ? $post_body["last_name"] : null; // last name is optional


$sqlQuery = "call web_insert_review (?, ?, ?, ?)";
$results = $db->rawQuery($sqlQuery, [$place_id, $first_name, $last_name, $review_text]);

// TODO- error handling
?>

