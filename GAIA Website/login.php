<?php

session_start();

function getVal($val) {
    if(isset($val)) {
        return htmlspecialchars(trim($val));
    } else {
        return '';
    }
}

function getPost() {
    return htmlspecialchars(trim($_SERVER['PHP_SELF']));
}

function getDSN() {
    return 'mysql:host=localhost:8889;dbname=Gaia';
}

function getUser() {
    return 'root';
}

function getPassword() {
    return 'root';
}

function getPDO() {
    try {
        $dsn = getDSN();
        $user = getUser();
        $pass = getPassword();
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo 'Connection failed: ' , $e->getMessage();
    }
    return $pdo;
}

function checkValidLogin() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
        $pdo = getPDO();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $email = $_POST['email'];
        $password = $_POST['password'];


        $sql = "SELECT * FROM Users WHERE email = '".$email."' AND password = '".$password."'";

        $statement = $pdo->query($sql);

        if($statement->rowCount() > 0) {
            echo "Invalid Login";
        }
        else {
            $_SESSION['userID'] = $email;
            header('Location: layout.php');
            die();
        }

        }
        catch(PDOException $exc) {
            echo "Connection failed: " . $exc->getMessage();
            $pdo = null;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAIA Login Page</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    >
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rrel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
    >
</head>
<body>
    <div class="container">

        <div class="form-wrapper">

            <div class="banner">
                <h1><img src="My_project_1.png" alt="GAIA Logo" style="width:auto"></h1>
                <p>Welcome back explorer!</p>
            </div>
            <div class="green-bg">
                <button id="loginBtn" name="loginBtn" type="button">Login</button>
            </div>
            <form method="POST" class="login-form">
                <h1>Login to Account</h1>
                <div class="social-media">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <p>or use your email to login</p>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Username" id="username" name="username">
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email" id="email" name="email">
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placehlder="Password" id="password" name="password">
                </div>
                <div class="text">
                
                    <input id="loginBtn" onclick="test()" name="loginBtn" type="submit" value="Login">
               
                </div>
            </form>
        </div>
    </div>
    <script src="loginscript.js"></script>
</body>
</html>
