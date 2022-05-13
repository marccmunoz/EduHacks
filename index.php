<?php
    if(isset($_COOKIE["PHPSESSID"])){
        session_start();
        if(isset($_SESSION["username"])){
            $usuari = $_SESSION['username'];
            header("Location:./home.php");
        }
        else{
            header("Location:./index.php");
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
        <form action="./lib/login.php" method="POST">
        <div class="illustration"><a href="./index.php"><img id="logo" src="./img/Eduhacks.png" height=”250” width=200></a></div>
        <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Username/Email"></div>
        <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"></div>
        <?php
        if(isset($_GET['error'])){
            if($_GET['error']==0){
                echo "<div class='ok'>Contraseña cambiada</div>";
            }
            if($_GET['error']==1){
                echo "<div class='bad'>Login incorrecto</div>";
            }
            if($_GET['error']==2){
                echo "<div class='ok'>Cuenta activada con éxito</div>";
            }
            if($_GET['error']==3){
                echo "<div class='ok'>Registro realizado, activa tu cuenta mediante el correo</div>";
            }
        }
        ?>
        <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="login">Log In</button></div>
        <a href="./register.php" class="createacc">Don’t have an account yet? Sign Up</a>
        <a href="./formReset.php" class="createacc">Forgot password?</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

