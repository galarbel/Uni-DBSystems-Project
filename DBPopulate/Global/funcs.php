<?php

function insertToTempTable($foursquare_id, $name, $address, $lattitude, $longitude, $country_code,
                           $city, $state, $country_name) {
    global $db;

    $format =
        "
      INSERT INTO DbMysql12.temp_table_1 
        (foursqaure_id, 
        name,
        address,
        lattitude,
        longitude,
        country_code,
        city,
        state,
        country_name) 
        VALUES 
          ('%s', 
          '%s', 
          '%s', 
          %d, 
          %d, 
          '%s',
          '%s', 
          '%s', 
          '%s')
        ";

    $sql_statement = sprintf($format, $foursquare_id, $name, $address, $lattitude, $longitude, $country_code,
        $city, $state, $country_name);

    echo $sql_statement;
}

function getVenusFromFourSquare($lati,$longi) {
    global $fourSquareSearchURL;

    $url = $fourSquareSearchURL . "&limit=50&ll=" . $lati . "," . $longi;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}



?>