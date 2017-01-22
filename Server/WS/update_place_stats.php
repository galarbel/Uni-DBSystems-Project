<?php

include_once '../Global/config.php';

$sqlUpdate = "call web_upd_place_stats (?, ?, ?, ?, ?)";

$place_id = null;
$check_count = null;
$users_count = null;
$likes = null;
$rating = null;

if (isset($_REQUEST["place_id"]) && is_numeric($_REQUEST["place_id"])) {
    $place_id = $_REQUEST["place_id"];
}

$sqlQuery = "SELECT foursquare_id FROM Places WHERE place_id = ?";

$results = $db->rawQuery($sqlQuery, [$place_id]);
$foursquare_id  = isset( $results[0]["foursquare_id"]) ?  $results[0]["foursquare_id"] : 0;

$pStats = json_decode(getVenueStatsFromFourSquare($foursquare_id ));

$venue = $pStats->response->venue;

$check_count = isset($venue->stats->checkinsCount) ? $venue->stats->checkinsCount : 0;
$users_count = isset($venue->stats->usersCount) ? $venue->stats->usersCount : 0;
$likes = isset($venue->likes->count) ? $venue->likes->count : 0;
$rating = isset($venue->rating) ? $venue->rating : null;

$results = $db->rawQuery($sqlUpdate, [$place_id,$check_count,$users_count,$likes,$rating]);

#echo json_encode($pStats);
include_once 'get_location_page_details.php';


?>


