<?php
session_start();


error_reporting(E_ALL);
ini_set('display_errors', '1');

const APIKEY = 'dd55550de0cf307d24b5d8b3c0754839';

function convertFarenheit($val) {
    return (($val - 273.15) * (9/5) + 32);
}
function getWeather() {
    $city_name = "St. Louis";

    $api_url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city_name . '&appid=' . APIKEY;

    $weather_data = json_decode(file_get_contents($api_url), true);

    $temp = $weather_data['main']['temp'];

    $feelsLike = $weather_data['main']['feels_like'];

    $tempMin = $weather_data['main']['temp_min'];

    $tempMax = $weather_data['main']['temp_max'];

    $description = $weather_data['weather']['0']['description'];

    $windSpeed = $weather_data['wind']['speed'];

    $windDegree = $weather_data['wind']['deg'];

    $weatherType = $weather_data['weather']['0']['main'];

    $windDirection = 0;

    $weatherReturnData = [$temp, $feelsLike, $tempMin, $tempMax];

    $weatherReturnData = array_map('convertFarenheit', $weatherReturnData);

    echo '</prev>';
    echo '<prev>';

    $temp = $weatherReturnData[0];

    $feelsLike = $weatherReturnData[1];

    $tempMin = $weatherReturnData[2];

    $tempMax = $weatherReturnData[3];

    return ['temperature' => $temp, 'feelsLike' => $feelsLike, 'temperatureMin' => $tempMin, 'temperatureMax' => $tempMax,
     'description' => $description, 'weatherType' => $weatherType,'windDirection' => $windDirection, 'windSpeed' => $windSpeed];
}

$username = 'jaredchristopher';
$location = 'St. Louis';
$graph = getWeather();
$temp = $graph["temperature"];
$windSpeed = $graph["windSpeed"];
$weatherType = $graph["weatherType"];

$posts = 1;
$postVerb = "posts";

if ($posts==1)
        {
            $postVerb = "post";
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAIA Profile</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="profilestyle.css">
</head>

<body>
<!-- left sidebar -->
    <div class="w3-sidebar w3-light-blue w3-bar-block" style="width:180px">
        <a href="layout.php"><img src="logo1.png" alt="GAIA" style="width:100%"></a>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="layout.php"><img src="homeicon.png" alt="home" style="width:20%"> Home</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="profile.php"><img src="profileicon.png" alt="profile" style="width:20%"> Profile</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="explore.php"><img src="explore.png" alt="explore" style="width:20%"> Explore</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="post.php"><img src="plusicon.png" alt="post" style="width:20%"> Post</a></p>
    </div>

 <!-- main -->
    <div style="margin-left:185px; margin-right:1%; padding:1px 16px;height:1000px; text-align:center">
    <img src="globeicon.png" alt="globe" style="height:15%; width:auto; margin-top:5px">
    <a href="post.php"><img src="plusicon.png" alt="plus" style="height:4%; width:auto; margin-top:60px"></a>

    <p class="p1">
        <div class="w3-panel">
        <h2><?php echo $username; ?></h2>           
        <h3><?php echo $posts . " " . $postVerb?></h3>
    </p>  
        </div> 

<!-- posts -->
    <div class="card">
        <img src="sky2.jpeg" alt="sky2">
        <h3><?php echo $location . " - " ?></h3>
        <h3><?php echo $weatherType . ", " .number_format($windSpeed, 0) . "mph wind". ", " . number_format($temp, 0)?><span id="F">&#8457;</span></h3>           
    </div>

</body>

</html>