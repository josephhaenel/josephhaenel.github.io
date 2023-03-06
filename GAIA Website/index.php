<?php
session_start();

function getVal($val) {
    if(isset($val)) {
        return htmlspecialchars(trim($val));
    } else {
        return '';
    }
}
//hello
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

function sqlAddToUsersQuery() {
    return "INSERT INTO Users
            (username, password, email, creation_date)
            VALUES
            (?, ?, ?, ?);";
}

function insertData() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $pdo = getPDO();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $email = $_POST['email'];
            $password = $_POST['password'];
            $username = $_POST['username'];
            $dateTime = date('d-m-y h:i:s');

            if (!preg_match("/^[a-zA-Z1-9-' ]*$/",$username)) {
                echo '<center><prev> Only letters and whitespace allowed in username</prev></center>';
            }
            $sql = "SELECT * FROM Users WHERE email = '".$email."'";

            $statement = $pdo->query($sql);

            if($statement->rowCount() > 0) {
                echo '<center><prev>Email already in use</prev></center>';
                return 0;
            }

            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@[0-9]@', $password);
            $specialCharacters = preg_match('@[^\w]@', $password);

            if(!$uppercase || !$lowercase || !$specialCharacters || !$number || strlen($password) < 8) {
                echo "<center><prev>Passwords should be atleast 8 characters long and contain a letter, number, and special character.</prev></center>";
                return 0;
            }
            // if($_POST['password'] != $_POST['confirmPassword']) {
            //     echo '<center><prev>Passwords do not match.</prev></center>';
            //     return 0;
            // }

            $params = [getVal($username) , getVal($password), getVal($email), $dateTime];
            $sql = sqlAddToUsersQuery(); 
            $pdoStm = $pdo->prepare($sql);
            $pdoStm->execute($params);
            header('Location: login.php');
            echo 'Successfully registered';
        }   
        catch(PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
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
    <title>GAIA Sign-In Page</title>
    <link rel="stylesheet" href="newstyle.css">
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
                <p>Sign-up to connect with the world through GAIA!</p>
            </div>
            <div class="green-bg">
                <button id="signUpBtn" type="button">Sign-up</button>
            </div>
            <form action="<?php getPost(); ?>" method="POST" class="signup-form">
                <?php insertData(); ?>
                <h1>Create Account</h1>
                <div class="social-media">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <p>or use your email for registration</p>
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
                    <!--<a href="http://localhost:8888/CS%20234/Gaia/login.php"> -->
                    <input id="signUpBtn" onclick="test()" name="signUpBtn" type="submit" value="Sign-up">
                   
                <div class="text">
                    <a href="http://localhost:8888/CS%20234/Repos/ehacks-project/login.php">
                    <p>Login</p>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
