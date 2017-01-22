<?php

include_once '../Global/config.php';

$sqlQuery = "SELECT place_id, foursquare_id from Places where place_id not in (select place_id from Places_Info) limit 200";

$places = $db->rawQuery($sqlQuery);

while (sizeof($places) > 0) {
    foreach ($places as $place) {
        $id = $place["place_id"];
        $fId = $place["foursquare_id"];
        $result = json_decode(getVenueInformationFromFourSquare($fId));

        $v = $result->response->venue;


        //place info
        $address    = $v->location->address;
        $city       = $v->location->city;
        $state      = $v->location->state;
        $country    = $v->location->cc;

        $phone      = $v->contact->formattedPhone;

        $url        = $v->url;

        $price      = $v->price->tier;


        $infoParams = [$id, $address, $city, $state, $country, $url, $phone, $price];

        $db->rawQuery("call dbPopulate_ins_places_info (?, ?, ?, ?, ?, ?, ?, ?)", $infoParams);

        //categories
        if (isset($v->categories)) {
            foreach ($v->categories as $category) {
                $categoryParams = [$id, $category->name];
                $db->rawQuery("call dbPopulate_ins_places_to_category (?, ?)", $categoryParams);
            }
        }


        //get stats
        if (isset($v->stats) && isset($v->likes)) {
            $checkinCount   = $v->stats->checkinsCount;
            $userCount      = $v->stats->usersCount;
            $likes          = $v->likes->count;
            $rating         = $v->rating;

            $statsParams = [$id, $checkinCount, $userCount, $likes, $rating];

            $db->rawQuery("call dbPopulate_ins_places_stats (?, ?, ?, ?, ?)", $statsParams);
        }

        //get photo
        if (isset($v->bestPhoto)) {
            $photoPrefix    = $v->bestPhoto->prefix;
            $photoSuffix    = $v->bestPhoto->suffix;
            $photoWidth     = $v->bestPhoto->width;
            $photoHeight    = $v->bestPhoto->height;

            $photoParams = [$id, $photoPrefix, $photoSuffix, $photoWidth, $photoHeight];

            $db->rawQuery("call dbPopulate_ins_places_photo (?, ?, ?, ?, ?)", $photoParams);
        }

        //reviews
        if (isset($v->tips->groups[0])) {
            foreach ($v->tips->groups[0]->items as $review) {

                $reviewFirstName    = $review->user->firstName;
                $reviewLastName     = $review->user->lastName;

                $reviewCreatedTime  = $review->createdAt;
                $reviewText         = $review->text;
                $reviewLikes        = $review->likes->count;

                $reviewParams = [$id, $reviewFirstName, $reviewLastName, $reviewText,$reviewLikes, $reviewCreatedTime];

                $db->rawQuery("call dbPopulate_ins_review (?, ?, ?, ?, ?, ?)", $reviewParams);
            }
        }

        usleep(20);
    }

    $places = $db->rawQuery($sqlQuery);
}


?>


