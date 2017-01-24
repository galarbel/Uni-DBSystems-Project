<?php

/** Sets the 'categoriesArray' property of a place json
 * @param $place - json that contains all of the venue's data retrieved from the database
 */
function parsePlaceCategories(&$place) {
    $place["categoriesArray"] = explode(";", $place["categories"]);
    unset($place["categories"]);
}

/** Sets the 'photo' property of a place json
 * @param $place - json that contains all of the venue's data retrieved from the database
 */
function parsePlacePhoto(&$place) {
    //concatenate photo details of the venue to create 'photo', that will be used as an image url by the client
    $place["photo"] = $place["photo_prefix"] . $place["photo_width"] . 'x' . $place["photo_height"] . $place["photo_suffix"];
    unset($place["photo_prefix"], $place["photo_width"], $place["photo_height"], $place["photo_suffix"]);
    //TODO if one is missing- replace with default photo url
    if ($place["photo"] == "x") {
        $place["photo"] = "https://cdn0.iconfinder.com/data/icons/kameleon-free-pack-rounded/110/Food-Dome-512.png";
    }
}

/** Executes a curl call
 * @param $url
 * @returns the response
 */
function curlCall($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/** Gets the current stats (columns of a venue in FourSquare
 * @param $id - the FourSquare id of the place that should be updated
 * @return the venue's updated stats
 */
function getVenueStatsFromFourSquare($id) {
    global $fourSquareInfoFormatURL;
    $fourSquare_v = date('Ymd');
    $url = sprintf($fourSquareInfoFormatURL . "&v=" . $fourSquare_v, $id);
    $curlResult = curlCall($url);

    return $curlResult;
}

/** Return BadRequest (400) to the client with a relevant error message.
 * @param {string} $errorMessage - the error message to be printed
 * @exits with http status of 400-bad request and prints the given error message
 */
function badRequest($errorMessage) {
    http_response_code(400);
    echo $errorMessage;
    die;
}

/** Gets a numeric parameter value.
 * @param {array} $requestParams - the array that should contain the specified param
 * @param {string} $paramName - name of the parameter
 * @param {boolean} $isRequired
 * @param {string} $defaultValue - value to be returned if the param is optional
 * @returns the numeric value of the parameter or its default value,
 *          exits with http status of 400-bad request - if the parameter is required but not on the request or not numeric
 */
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