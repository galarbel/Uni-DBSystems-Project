<?php

header('Content-type: application/json');

/*------Four Square API--------*/
$url = "https://api.foursquare.com/v2/venues/search";

$client_id = "VVFPKXJBWNMA1KQUJ50KYM5W5AK0IEIBITKTJPKVX3PQQDVR";
$client_secret = "Q4FNEYLWT0OU0JWQDXEERMKPL1OOZIE0JBXCDTA0A0J05EDN&v=20161231";
/*-----------------------------*/

/*-------Search Params--------*/
$date = "20161231";

$latitude = "40.7";
$longitude = "-74";

/*----------------------------*/

/*----Build URL for API call---*/
$apiCallURL = $url;
$apiCallURL = $apiCallURL . "?client_id=" . $client_id;
$apiCallURL = $apiCallURL . "&client_secret=" . $client_secret;

$apiCallURL = $apiCallURL . "&v=" . $date;
$apiCallURL = $apiCallURL . "&ll=" . $latitude . "," . $longitude;

/*
echo $apiCallURL;
die;
*/

 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 $output = curl_exec($ch); 
 curl_close($ch); 
 
 echo $output;
 

?>