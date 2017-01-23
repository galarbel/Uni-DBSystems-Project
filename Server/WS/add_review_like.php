<?php

include_once '../Global/config.php';

header('Content-type: application/json');

$review_id = getNumericParamOrDefault($_REQUEST, "review_id", true, null);
$sqlQuery = "call web_add_like_to_review(?)";
$results = $db->rawQuery($sqlQuery, [$review_id]);

?>