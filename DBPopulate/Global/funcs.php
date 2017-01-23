<?php

function curlCall($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

function getVenusFromFourSquare($lati,$longi) {
    global $fourSquareSearchURL;

    $url = $fourSquareSearchURL . "&ll=" . $lati . "," . $longi;

    //echo $url; die;
    $curlResult = curlCall($url);

    return $curlResult;
}


function getVenueInformationFromFourSquare($id) {
    global $fourSquareInfoFormatURL;

    $url = sprintf($fourSquareInfoFormatURL, $id);

    //echo $url; die;
    $curlResult = curlCall($url);

    return $curlResult;
}

function insertToTempTable($foursquare_id, $name, $address, $latitude, $longitude, $country_code,
                           $city, $state, $country_name, $category,$verified) {
    global $db;
    $format =
        "
         INSERT IGNORE INTO DbMysql12.temp_table_2 
          (foursquare_id, 
          name,
          address,
          latitude,
          longitude,
          country_code,
          city,
          state,
          country_name,
          primary_cate,
          verified) 
          VALUES 
            (?,
            ?, 
            ?, 
            ?, 
            ?, 
            ?,
            ?, 
            ?, 
            ?,
            ?,
            ?);
        ";

    $params = [
        $foursquare_id,
        $name,
        $address,
        $latitude,
        $longitude,
        $country_code,
        $city,
        $state,
        $country_name,
        $category,
        $verified];

    //echo $sql_statement;
    //die;
    return $db->rawQuery($format, $params);
}


?>