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

$fourSquare_client_id = "VVFPKXJBWNMA1KQUJ50KYM5W5AK0IEIBITKTJPKVX3PQQDVR";
$fourSquare_client_secret = "Q4FNEYLWT0OU0JWQDXEERMKPL1OOZIE0JBXCDTA0A0J05EDN";

$fourSquare_v = "20161231";

$fourSquareSearchURL = $fourSquareAPISearch;
$fourSquareSearchURL .= "?client_id=" . $fourSquare_client_id;
$fourSquareSearchURL .= "&client_secret=" . $fourSquare_client_secret;
$fourSquareSearchURL .= "&v=" . $fourSquare_v;
/*-----------------------------*/
?>