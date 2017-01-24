<?php

include_once '../Global/config.php';

/** API for finding a venue by text search on its name.
 *
 *  Method : GET
 *
 *  Required parameters:
 *  text. The user's input text to search for.
 *
 *  Returns:
 *  On success - Success (200) with a list of at most 100 places that matches the input by name,
 *                              and a subset of their data to be displayed on the results page.
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */


if (!isset($_REQUEST["text"]) ) {
    badRequest("missing 'text' parameter");
}

// parse input text and generate a string compatible with boolean mode fulltext search.
$words = preg_split('/\s+/', trim($_REQUEST["text"]), -1, PREG_SPLIT_NO_EMPTY);
for ($i = 0; $i < sizeof($words); $i++) {
    $words[$i] = "+" . $words[$i] . "*";
}
$text_searched = join(" ", $words);

// call 'web_get_places_by_text_search' stored procedure to execute the search
$sqlQuery = "call web_get_places_by_text_search (?)";
$results = $db->rawQuery($sqlQuery, [$text_searched]);

// convert results to client compatible form.
for ($i = 0; $i < sizeof($results); $i++) {
    parsePlaceCategories($results[$i]);
    parsePlacePhoto($results[$i]);
}

header('Content-type: application/json');
echo json_encode($results);

?>