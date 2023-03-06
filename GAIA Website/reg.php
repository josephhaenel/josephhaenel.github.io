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
            // Add header('Location: login.php');
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
        <meta charset="utf-8">
</head>
<body>
    <form action="<?php getPost(); ?>" method="POST">

        <?php insertData(); ?>
        <label>Username</label>
        <input id="username" name="username" type="text" placeholder="Username" required>
        <label>Email</label>
        <input id="email" name="email" type="text" placeholder="Ex. username@.gmail.com" required>
        <input id="password" name="password" type="password" placeholder="Password" required>
        <input id="passwordConfirm" name="passwordConfirm" type="password" placeholder="Confirm Password">
        <input type="submit" value="Submit">
    </form>

</body>
</html>
