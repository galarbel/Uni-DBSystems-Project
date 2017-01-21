<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["place_id"])) {
    echo 'missing \'place_id\' parameter'; die;
}
if (!isset($_REQUEST["review_text"])) {
    echo 'missing \'review_text\' parameter'; die;
}
if (!isset($_REQUEST["first_name"])) {
    echo 'missing \'first_name\' parameter'; die;
}

header('Content-type: application/json');

$place_id = $_REQUEST["place_id"];
$review_text = $_REQUEST["review_text"];
$first_name = $_REQUEST["first_name"];
$last_name = isset($_REQUEST["last_name"]) ? $_REQUEST["last_name"] : null; // last name is optional


$sqlQuery = "call web_insert_review (?, ?, ?, ?)";
$results = $db->rawQuery($sqlQuery, [$place_id, $first_name, $last_name, $review_text]);

// TODO- error handling
?>

