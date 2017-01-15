<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["review_id"])) {
    echo 'please put "?review_id=ID" at the end of the url'; die;
}

header('Content-type: application/json');
$review_id = $_REQUEST["review_id"];

// currently can only add likes, will be decided on Tuesday
$sqlQuery = "UPDATE Reviews SET likes_count = likes_count +1
             WHERE review_id = ?";
$results = $db->rawQuery($sqlQuery, [$review_id]);

echo json_encode($results);

?>