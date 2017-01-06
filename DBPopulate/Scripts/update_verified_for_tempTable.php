<?php

include_once '../Global/config.php';

//$latitude = 40.730610;
//$longitude = -73.935242;

$results = $db->rawQuery("SELECT foursquare_id from temp_table_2 where verified is null limit 200");

while (sizeof($results) > 0) {
    foreach ($results as $v) {
        $id = $v["foursquare_id"];
        $vInfo = json_decode(getVenueInformationFromFourSquare($id));

        $verified = isset($vInfo->response->venue->verified) ? $vInfo->response->venue->verified : 0;

        $results = $db->rawQuery("UPDATE temp_table_2 SET verified = ? where foursquare_id = ?", [$verified, $id]);
    }


    $results = $db->rawQuery("SELECT foursquare_id from temp_table_2 where verified is null limit 200");
}

?>