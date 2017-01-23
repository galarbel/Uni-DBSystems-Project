<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["review_id"]) || !is_numeric($_REQUEST["review_id"])) {
    badRequest("missing 'review_id' parameter or not numeric");
}
$review_id = $_REQUEST["review_id"];

header('Content-type: application/json');

$sqlQuery = "call web_add_like_to_review(?)";
$results = $db->rawQuery($sqlQuery, [$review_id]);

//echo json_encode($results);

?>