<?php

include_once '../Global/config.php';

//$latitude = 40.730610;
//$longitude = -73.935242;

for ($latitude =    -90.000000; $latitude <= 90.000000; $latitude = $latitude + 0.05 ){
    for ($longitude =  -180.000000; $longitude <= 180.000000; $longitude = $longitude + 0.05){
    

    $results = getVenusFromFourSquare($latitude,$longitude);



    $resultsArr = json_decode($results);
    $vArr = $resultsArr->response->venues;

    //echo "Return Code:" . $resultsArr->meta->code . "<br>";
    //echo "Venues:" . sizeof($vArr). "<br>";
    //echo json_encode($vArr[0]);

        foreach ($vArr as $v) {
            $address = "NULL";
            $city = "NULL";
            $state = "NULL";
            $id = $v->id;
            $name = $v->name;
            if (isset($v->location->address)){
                $address = $v->location->address;
            }
            $flatitude = $v->location->lat;
            $flongitude = $v->location->lng;
            $country_code = $v->location->cc;
            if (isset($v->location->city)){
                $city = $v->location->city;
            }
            if (isset($v->location->state)){
                $state = $v->location->state;
            }
            $country_name = $v->location->country;
            insertToTempTable($id, $name, $address, $flatitude, $flongitude, $country_code, $city, $state, $country_name);
            //die;
        }
    // sleep for 10 seconds
    sleep(10);
    //echo $latitude . ", " . $longitude . " NEXT!";
    }
}

?>