<?php

//session_start();


const APIKEY = 'dd55550de0cf307d24b5d8b3c0754839';

# https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/Staunton?unitGroup=metric&include=events%2Cdays%2Chours%2Ccurrent%2Calerts&key=LWWTJ77JV2JEWQQV9HDTF8AYY&contentType=json

function getPostback() {
    $post = htmlspecialchars(trim($_SERVER['PHP_SELF']));
    return $post;
}

function convertFarenheit($val) {
    return (($val - 273.15) * (9/5) + 32);
}

function getWindDirection($windDegree) {
        if ($windDegree >= 337.5) {
            $windDirection = 'North';
        } else
        if ($windDegree >= 292.5) {
            $windDirection = 'Northwest';
        } else
        if ($windDegree >= 247.5) {
            $windDirection = 'West';
        } else
        if ($windDegree >= 202.5) {
            $windDirection = 'Southwest';
        } else
        if ($windDegree >= 157.5) {
            $windDirection = 'South';
        } else
        if ($windDegree >= 112.5) {
            $windDirection = 'Southeast';
        } else
        if ($windDegree >= 67.5) {
            $windDirection = 'East';
        } else
        if ($windDegree >= 22.5) {
            $windDirection = 'Northeast';
        } else
        if ($windDegree >= 0) {
            $windDirection = 'North';
        } else {
            $windDirection = 'UnknownError';
        }
    return $windDirection;
}

function getWeather() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $city_name = $_POST['location'];

        $api_url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city_name . '&appid=' . APIKEY;

        $weather_data = json_decode(file_get_contents($api_url), true);
        
        // echo '<prev>';
        // print_r($weather_data);

        $temp = $weather_data['main']['temp'];

        $feelsLike = $weather_data['main']['feels_like'];

        $tempMin = $weather_data['main']['temp_min'];

        $tempMax = $weather_data['main']['temp_max'];

        $description = $weather_data['weather']['0']['description'];

        $windSpeed = $weather_data['wind']['speed'];

        $windDegree = $weather_data['wind']['deg'];

        $weatherType = $weather_data['weather']['0']['main'];

        $windDirection = getWindDirection($windDegree);

        $weatherReturnData = [$temp, $feelsLike, $tempMin, $tempMax,];

        $weatherReturnData = array_map('convertFarenheit', $weatherReturnData);

        echo '</prev>';
        echo '<prev>';

        $temp = $weatherReturnData[0];

        $feelsLike = $weatherReturnData[1];

        $tempMin = $weatherReturnData[2];

        $tempMax = $weatherReturnData[3];

        return ['temperature' => $temp, 'feelsLike' => $feelsLike, 'temperatureMin' => $tempMin, 'temperatureMax' => $tempMax,
         'description' => $description, 'weatherType' => $weatherType,'windDirection' => $windDirection, 'windSpeed' => $windSpeed, 'city_name' => $city_name];
    }
}


//$graph = getWeather();
//$temp = $graph['weatherType'];

# ATBBsD4gBLBg9FHfvTrTXDDrK7Fv2D6E7754

?>

<!DOCTYPE html>
<html>


<body>


</body>

</html>