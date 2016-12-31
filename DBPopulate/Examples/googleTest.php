<?php

header('Content-type: application/json');


$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670,151.1957&radius=500&types=food&name=cruise&key=AIzaSyDBD9a5p47igfeffKOIz1Hc7L8SBcsLzu8";



 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 $output = curl_exec($ch); 
 curl_close($ch); 
 
 echo $output;
 

?>