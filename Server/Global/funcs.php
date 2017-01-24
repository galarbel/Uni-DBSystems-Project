<?php

function parsePlaceCategories(&$place) {
    $place["categoriesArray"] = explode(";", $place["categories"]);
}

function parsePlacePhoto(&$place) {
    $place["photo"] = $place["photo_prefix"] . $place["photo_width"] . 'x' . $place["photo_height"] . $place["photo_suffix"];
}

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


function getVenueStatsFromFourSquare($id) {
    global $fourSquareInfoFormatURL;
    $fourSquare_v = date('Ymd');
    $url = sprintf($fourSquareInfoFormatURL . "&v=" . $fourSquare_v, $id);
    $curlResult = curlCall($url);

    return $curlResult;
}

function badRequest($errorMessage) {
    http_response_code(400);
    echo $errorMessage;
    die;
}

function getNumericParamOrDefault($requestParams, $paramName, $isRequired, $defaultValue) {
    if(isset($requestParams[$paramName])) {
        $paramValue = $requestParams[$paramName];
        if(is_numeric($paramValue))  {
            return $paramValue;
        } else {
            badRequest("parameter '" .$paramName . "'' is not a number");
        }
    } else if (!$isRequired){
        return $defaultValue;
    } else {
        badRequest("parameter '" .$paramName . "'' is missing");
    }
}
?>