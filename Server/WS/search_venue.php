<?php

include_once '../Global/config.php';

$sqlQuery = "call web_get_places_by_text_search (?)";

if (isset($_REQUEST["text"]) ) {
    $words = preg_split('/\s+/', trim($_REQUEST["text"]), -1, PREG_SPLIT_NO_EMPTY);
    for ($i = 0; $i < sizeof($words); $i++) {
        $words[$i] = "+" . $words[$i] . "*";
    }
    $text_searched = join(" ", $words);
} else {
    die;
}

header('Content-type: application/json');

$results = $db->rawQuery($sqlQuery, [$text_searched]);

echo json_encode($results);

?>