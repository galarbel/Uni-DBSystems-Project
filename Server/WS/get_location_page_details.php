<?php

include_once '../Global/config.php';

/** API for getting a possible replacements for one of the suggested meals in a culinary day plan.
 *
 *  Method : GET
 *
 *  Required parameters:
 *  place_id - numeric
 *
 *  Returns:
 *  On success - Success (200) with all the place's info relevant to be displayed in a 'place specific' page
 *  On invalid params - Bad Request (400) with an error message
 *  On DB error - Internal Server Error (500) with an error message
 */
$place_id = getNumericParamOrDefault($_REQUEST, "place_id", true, null);

$getPlaceDataQuery = "call web_get_place_data_by_id (?)";
$place_data = $db->rawQuery($getPlaceDataQuery, [$place_id])[0];
parsePlacePhoto($place_data);
parsePlaceCategories($place_data);

$db = new MysqliDb ($DBServer, $DBUsername, $DBPassword, $DBName);
$getReviewsQuery = "call web_get_reviews_by_place_id (?)";
$place_reviews = $db->rawQuery($getReviewsQuery, [$place_id]);
$place_data["reviews_count"]= count($place_reviews);
$place_data["reviews"]= $place_reviews;

header('Content-type: application/json');
echo json_encode($place_data);

?>