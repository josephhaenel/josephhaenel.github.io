<?php
session_start();
$_SESSION['userID'] = 'jarchri@siue.edu';
/*HTML I REFER TO:
<form action="upload.php" method="post" enctype="multipart/form-data">
<input type="file" name="picture" id="picture">
<input type="submit" value="Upload Image" name="uploadPicture">
</form> */

function upload() {
    if ( isset($_POST['uploadPicture'])) {
        $file = $_FILES['picture'];

        $fileName = $_FILES['picture']['name'];
        $fileTmpName = $_FILES['picture']['tmp_name'];
        $fileSize = $_FILES['picture']['size'];
        $fileError = $_FILES['picture']['error'];
        $fileType = $_FILES['picture']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allow = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allow)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    // $pdo = getPDO();
                    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // $email = $_SESSION['userID'];

                    // $sql = "INSERT INTO Posts VALUES (

                } else {
                    echo 'File size is too large';
                }
            }else {
                echo 'There was an error uploading the file';
            }
        }
        else{
            echo 'You cannot upload files of this type';
        }

    }
}

?>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="homestyle.css">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="post.css">
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

<!--  upload -->

<div class="container">
<div class="background">
  <div class="content">
  <div id="wrapper">

        <! specify the encoding type of the form using the 

                enctype attribute >
        <form action="<?php upload() ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="picture" id="picture">
        <input type="submit" value="Upload Image" name="uploadPicture" class="w-3">
        </form>
        
        <!-- <form method="POST" action="" enctype="multipart/form-data">        
            <input type="file" name="choosefile" value="" class="w3-bar-item w3-button w3-hover-green"/>
            <div>
                <a type="submit" name="uploadfile" class="w3-bar-item w3-button w3-hover-green w3-white">UPLOAD</a>
            </div>

        </form> -->

    </div>
  </div>
</div>
</div>


</body> 

</html>