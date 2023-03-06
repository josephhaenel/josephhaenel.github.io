<?php

session_start();


error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'WeatherData.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $graph = getWeather();
    $temp = $graph["temperature"];
    $feelsLike = $graph["feelsLike"];
    $windSpeed = $graph["windSpeed"];
    $weatherType = $graph["weatherType"];
    $city_name = '';
}
else {
    $temp = getWeather();
    $temp = 0;
    $feelsLike = 0;
    $windSpeed = 0;
    $weatherType = '';
    $city_name = '';
} 
?>

<!-- -- -- HTML --  -- -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="homestyle.css">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
<!-- left sidebar -->
<div class="w3-sidebar w3-light-blue w3-bar-block" style="width:180px">
        <a href="layout.php"><img src="logo1.png" alt="GAIA" style="width:100%"></a>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="layout.php"><img src="homeicon.png" alt="home" style="width:20%"> Home</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="profile.php"><img src="profileicon.png" alt="profile" style="width:20%"> Profile</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="explore.php"><img src="explore.png" alt="exlplore" style="width:20%"> Explore</a></p>
        <p><a class="w3-bar-item w3-button w3-hover-green" href="post.php"><img src="plusicon.png" alt="post" style="width:20%"> Post</a></p>
</div>

<!-- right sidebar -->
<div class="w3-sidebar w3-bar-block w3-card w3-light-blue" style="width:180px;right:0;">

    <img src="travel.webp" alt="travel" style="width:60%; margin-left:40px ;margin-bottom:15px; margin-top:10px">
    <a class="w3-bar-item w3-button w3-hover-green" target="_blank" href="https://www.trivago.com/"><img src="trivago.png" alt="trivago" height="auto" width="100%"></a>
    <a class="w3-bar-item w3-button w3-hover-green" target="_blank" href="https://www.southwest.com/"><img src="southwestlogo.png" alt="southwest" height="auto" width="100%"></a>
    <a class="w3-bar-item w3-button w3-hover-green" target="_blank" href="https://www.booking.com/"><img src="bookinglogo.png" alt="booking" height="auto" width="100%"></a>
    <a class="w3-bar-item w3-button w3-hover-green" target="_blank" href="https://www.marriott.com/default.mi?scid=bab7e593-ab9f-40e7-945c-46c896724537&gclid=Cj0KCQiA9YugBhCZARIsAACXxeK4hzrVNdPiVUdbe4KycocoMp9S7O9DO_RNUXCr1CwcoetI9tQuF6AaAqkDEALw_wcB&gclsrc=aw.ds"><img src="marriott.png" alt="mariott" height="auto" width="100%"></a>
</div>

<!-- main format-->
<div style="margin-left:185px; margin-right:185px; padding:1px 16px;height:1000px; text-align:center">
<img src="logobanner.png" alt="logobanner" height="auto" width="55%">

<!-- search -->
<h1>   
    <form action="<?php getPostBack(); ?>" method="POST">
       <input style="width:90% ; background-color: rgba(75, 182, 214, 0.296)" type="search" placeholder="Search for a city..." name='location' id='location'>
   </form>
<h1> 

<!-- weather widget -->

<div class="container">
<div class="background">
  <div class="Circle1"></div>
  <div class="Circle2"></div>
  <div class="Circle3"></div>
  <div class="content">
    <h1 class="Condition"><?php echo $weatherType?></h1>
    <h1 class="Temp"><?php echo number_format($temp, 0)?><span id="F">&#8457;</span></h1>
    <h1 class="Time"><?php echo "Wind: " . number_format($windSpeed, 0) . "mph"; ?></h1>
    <h1 class="Location"><?php echo "Feels like: " . number_format($feelsLike, 0)?><span id="F">&#8457;</span></h1>
  </div>
</div>
</div>

<!-- upcoming events -->
<div class="card">
    <h2>Upcoming:</h2>
    <h3>
        March 28-April 18: Best time to see Mercury             
    </h3>
    <h3>
        April 20: Rare hybrid solar eclipse of the sun           
    </h3>
    <h3>
        August 12: The persed meteor shower         
    </h3>
    <h3>
        August 30: Biggest full moon of 2023           
    </h3>
    <h3>
        October 14: Annular solar eclipse of the sun         
    </h3>
    <h3>
        November 9: A beautiful venus and predawn moon tableau         
    </h3>
    <h3>
        November 19 - December 24: Geminid meteor shower         
    </h3>
</div>


</body>

</html>