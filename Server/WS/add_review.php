<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["place_id"])) {
    echo 'missing \'place_id\' parameter'; die;
}
if (!isset($_REQUEST["review_text"])) {
    echo 'missing \'review_text\' parameter'; die;
}


header('Content-type: application/json');
$place_id = $_REQUEST["place_id"];
$review_text = $_REQUEST["review_text"];

$sqlQuery = "INSERT INTO Reviews (review_id, place_id, review_text, likes_count)
             VALUES ((SELECT MAX(R.review_id)+1 FROM Reviews as R), ?, ?, 0)";
$results = $db->rawQuery($sqlQuery, [$place_id, $review_text]);

echo json_encode($results);

?>