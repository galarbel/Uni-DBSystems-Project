<?php

header('Content-type: application/json');


$url = "https://api.foursquare.com/v2/venues/search?ll=40.7,-74&client_id=VVFPKXJBWNMA1KQUJ50KYM5W5AK0IEIBITKTJPKVX3PQQDVR&client_secret=Q4FNEYLWT0OU0JWQDXEERMKPL1OOZIE0JBXCDTA0A0J05EDN&v=20161231";



 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 $output = curl_exec($ch); 
 curl_close($ch); 
 
 echo $output;
 

?>