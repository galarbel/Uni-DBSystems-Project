<?php

include_once '../Global/config.php';

if (isset($_REQUEST["place_id"]) ) {
    $place_id = $_REQUEST["place_id"];
} else {
    echo "missing 'place_id' parameter"; die;
}

header('Content-type: application/json');

$getPlaceDataQuery = "call web_get_place_data_by_id (?)";
$place_data = $db->rawQuery($getPlaceDataQuery, [$place_id])[0];
parsePlacePhoto($place_data);

$getReviewsQuery = "call web_get_reviews_by_place_id (?)";
$place_reviews = $db->rawQuery($getReviewsQuery, [$place_id]);
$place_data["reviews_count"]= count($place_reviews);
$place_data["reviews"]= json_decode(json_encode($place_reviews), true);

echo json_encode($place_data);

?>