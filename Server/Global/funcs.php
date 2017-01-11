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

    $curlResult = curlCall($url);

    return $curlResult;
}


function getVenueInformationFromFourSquare($id) {
    global $fourSquareInfoFormatURL;

    $url = sprintf($fourSquareInfoFormatURL, $id);

    $curlResult = curlCall($url);

    return $curlResult;
}


?>