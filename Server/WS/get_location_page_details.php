<?php

include_once '../Global/config.php';


$place_id = getNumericParamOrDefault($_REQUEST, "place_id", true, null);

$getPlaceDataQuery = "call web_get_place_data_by_id (?)";
$place_data = $db->rawQuery($getPlaceDataQuery, [$place_id])[0];
parsePlacePhoto($place_data);
parsePlaceCategories($place_data);

$db=new MysqliDb ($DBServer, $DBUsername, $DBPassword, $DBName);
$getReviewsQuery = "call web_get_reviews_by_place_id (?)";
$place_reviews = $db->rawQuery($getReviewsQuery, [$place_id]);
$place_data["reviews_count"]= count($place_reviews);
$place_data["reviews"]= $place_reviews;

header('Content-type: application/json');
echo json_encode($place_data);

?>