<?php 
    require_once("./lib/conectadb.php");

    if(isset($_COOKIE["PHPSESSID"])){
        session_start();
        header("Location:./home.php");
    }

    if(isset($_SESSION['username'])){
        header("Location:./home.php");
    }
    
    if(isset($_POST['register'])) {
        if (strlen($_POST['username']) >= 1 && strlen($_POST['email']) >= 1 && strlen($_POST['password']) >= 1 && strlen($_POST['verifypassword']) >= 1) {
            $db = conexionDB();
            $name = trim($_POST['username']);
            $email = trim($_POST['email']);
            if(isset($_POST['firstname'])){
                $firstname = trim($_POST['firstname']);
            }else{
                $firstname = "";
            }
            if(isset($_POST['lastname'])){
                $lastname = trim($_POST['lastname']);
            }else{
                $lastname = "";
            }
            $password = trim($_POST['password']);
            $verifypass = trim($_POST['verifypassword']);
            $creationdate = date('Y\/m\/d G:i:s');
            if($password==$verifypass){
                require_once("./lib/enviarMail.php");
                $password = password_hash($password, PASSWORD_DEFAULT);
                $randomHashReset=codigoVerificacion();
                $randomHash=codigoVerificacion();
                $active=0;
                $consulta = "INSERT INTO `users`(mail, username, passHash, userFirstName, userLastName, creationDate, active, activationCode, resetPassCode) VALUES (:email,:name,:password,:firstname,:lastname,:creationdate,:active, :randomHash, :randomHashReset);";
                $resultat = $db->prepare($consulta);
                $resultat->execute(array(':email' => $email, ':name' => $name, ':password' => $password, ':firstname' => $firstname, ':lastname' => $lastname, ':creationdate' => $creationdate, ':active' => $active, ':randomHash' => $randomHash , ':randomHashReset' => $randomHashReset));
                EmailRegister($email,$randomHash,1);
                header("Location: ../index.php?error=3");
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHacks</title>
    <link rel="icon" type="image/png" href="./img/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link href="http://fonts.cdnfonts.com/css/technology" rel="stylesheet">
</head>
<body>
    <div class="login-dark">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="illustration"><a href="./index.php"><img id="logo" src="./img/Eduhacks.png" height=”250” width=200></a></div>
            <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Username" required></div>
            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
            <div class="form-group"><input class="form-control" type="text" name="firstname" placeholder="First Name"></div>
            <div class="form-group"><input class="form-control" type="text" name="lastname" placeholder="Last Name"></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><input class="form-control" type="password" name="verifypassword" placeholder="Verify Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="register">Sign In</button></div>
            <a href="./index.php" class="createacc">Do you have account already? Log In</a>
            </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>