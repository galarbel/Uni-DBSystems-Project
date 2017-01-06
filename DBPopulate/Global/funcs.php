<?php

function insertToTempTable($foursquare_id, $name, $address, $latitude, $longitude, $country_code,
                           $city, $state, $country_name, $categories) {
    global $db;
    $format =
        "
         INSERT IGNORE INTO DbMysql12.temp_table_1 
          (foursqaure_id, 
          name,
          address,
          lattitude,
          longitude,
          country_code,
          city,
          state,
          country_name,
          categories) 
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
        $categories];

    //echo $sql_statement;
    //die;
    return $db->rawQuery($format, $params);
}

function getVenusFromFourSquare($lati,$longi) {
    global $fourSquareSearchURL;

    $url = $fourSquareSearchURL . "&ll=" . $lati . "," . $longi;

    //echo $url;
    //die;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

?>