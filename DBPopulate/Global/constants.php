<?php

/*-----Database Related Variables----*/
$DBServer	= "127.0.0.1:3305";
$DBUsername = "DbMysql12";
$DBPassword = "DbMysql12";
$DBName		= "DbMysql12";
/*-----------------------------------*/


/*------Four Square API--------*/
$fourSquareAPI = "https://api.foursquare.com/v2/venues";
$fourSquareAPISearch = $fourSquareAPI . "/search";

//$fourSquare_client_id = "VVFPKXJBWNMA1KQUJ50KYM5W5AK0IEIBITKTJPKVX3PQQDVR"; //quota reached?
//$fourSquare_client_secret = "Q4FNEYLWT0OU0JWQDXEERMKPL1OOZIE0JBXCDTA0A0J05EDN"; //quota reached?
$fourSquare_client_id = "3FLRXLDWATRJ3ZMDA1HXAWOYRC5DMCPPMSB2E1U00K32X1K0";
$fourSquare_client_secret = "QQ5HQZKQWPYM3X31WIPMZOPBI4HWSFUBEYDDKFT3WSRX50UD";

$fourSquare_v = "20161231";
$fourSquare_radius = "1000";
$foursquare_limit = "50";

//Venues
$fourSquareSearchURL = $fourSquareAPISearch;
$fourSquareSearchURL .= "?client_id=" . $fourSquare_client_id;
$fourSquareSearchURL .= "&client_secret=" . $fourSquare_client_secret;
$fourSquareSearchURL .= "&v=" . $fourSquare_v;
$fourSquareSearchURL .= "&radius=" . $fourSquare_radius;
$fourSquareSearchURL .= "&limit=" . $foursquare_limit;

//Venue Info
$fourSquareInfoFormatURL = $fourSquareAPI . "/%s";
$fourSquareInfoFormatURL .= "?client_id=" . $fourSquare_client_id;
$fourSquareInfoFormatURL .= "&client_secret=" . $fourSquare_client_secret;
$fourSquareInfoFormatURL .= "&v=" . $fourSquare_v;
/*-----------------------------*/
?>