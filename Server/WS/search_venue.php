<?php

include_once '../Global/config.php';

if (!isset($_REQUEST["text"]) ) {
    badRequest("missing 'text' parameter");
}

$words = preg_split('/\s+/', trim($_REQUEST["text"]), -1, PREG_SPLIT_NO_EMPTY);
for ($i = 0; $i < sizeof($words); $i++) {
    $words[$i] = "+" . $words[$i] . "*";
}
$text_searched = join(" ", $words);

$sqlQuery = "call web_get_places_by_text_search (?)";
$results = $db->rawQuery($sqlQuery, [$text_searched]);

for ($i = 0; $i < sizeof($results); $i++) {
    parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>