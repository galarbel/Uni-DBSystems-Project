<?php

include_once '../Global/config.php';

$latitude = 40.7;
$longitude = -74;

$results = getVenusFromFourSquare($latitude,$longitude);



$resultsArr = json_decode($results);
$vArr = $resultsArr->response->venues;

//echo "Return Code:" . $resultsArr->meta->code . "<br>";
//echo "Venues:" . sizeof($vArr);
//echo json_encode($vArr[0]);

foreach ($vArr as $v) {
    $id = $v->id;
    $name = $v->name;
    $address = $v->location->address;
    $lattitude = $v->location->lat;
    $longitude = $v->location->lng;
    $country_code = $v->location->cc;
    $city = $v->location->city;
    $state = $v->location->state;
    $country_name = $v->location->country;
    insertToTempTable($id, $name, $address, $lattitude, $longitude, $country_code, $city, $state, $country_name);
    die;
}


?>