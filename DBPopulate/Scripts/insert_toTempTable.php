<?php

include_once '../Global/config.php';

//$latitude = 40.730610;
//$longitude = -73.935242;

$USA_START_LATITUDE = 31.000000;

for ($latitude =    40.500000; $latitude <= 48.000000; $latitude = $latitude + 0.05 ){
    for ($longitude =  -125.000000; $longitude <= -70.000000; $longitude = $longitude + 0.05){
    

    $results = getVenusFromFourSquare($latitude,$longitude);



    $resultsArr = json_decode($results);
    $vArr = $resultsArr->response->venues;

    //echo "Return Code:" . $resultsArr->meta->code . "<br>";
    //echo "Venues:" . sizeof($vArr). "<br>";
    //echo json_encode($vArr[0]);

        foreach ($vArr as $v) {
            $id         = $v->id;
            $name       = $v->name;
            $flatitude  = $v->location->lat;
            $flongitude = $v->location->lng;

            $address        = (isset($v->location->address) ? $v->location->address : null);
            $city           = (isset($v->location->city) ? $v->location->city : null);
            $country_code   = (isset($v->location->cc) ? $v->location->cc : null);
            $state          = (isset($v->location->state) ? $v->location->state : null);
            $country_name   = (isset($v->location->country) ? $v->location->country : null);

            $categories = null;
            if (isset($v->categories)) {
                foreach ($v->categories as $cate) {
                    $categories = $cate->name;
                    break; //seems like there is only 1 category per place...
                }
            }

            insertToTempTable($id, $name, $address, $flatitude, $flongitude, $country_code, $city, $state, $country_name, $categories);
            //die;
        }

    usleep(50);
    echo $latitude . ", " . $longitude . " NEXT!<br>";
    flush();
    }
}

?>