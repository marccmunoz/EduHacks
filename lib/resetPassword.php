<?php
    require_once("./conectadb.php");
    $db = conexionDB();
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $codi_reset = $_POST['codi_reset'];
        $mail = $_POST['mail'];

        if($password1 == $password2){
            $sql = 'SELECT resetPassCode as codi ,mail FROM users where resetPassCode = :codi and mail = :mail;';
            $preparada = $db->prepare($sql);
            $preparada->execute(array(':codi' => $codi_reset, ':mail' => $mail));
            $fila = $preparada->fetch(PDO::FETCH_ASSOC);
            if (!isset($fila['codi'])){
                header("Location: ../index.php");
            }else{
                $password=password_hash($password1,PASSWORD_DEFAULT);
                $update = "update users SET passHash = :a WHERE mail = :b AND resetPassCode = :code ;";
                $preparada = $db->prepare($update);
                $preparada->execute(array(':a' => $password, ':b' => $mail , ':code' => $codi_reset));
                header("Location: ../index.php");
            }
        }
        header("Location: ../index.php?error=0");
    }
        
        
    if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['mail']) && isset($_GET['code'])){
        $mail = $_GET['mail'];
        $code = $_GET['code'];
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EduHacks</title>
        <link rel="icon" type="image/png" href="./img/icon.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link href="http://fonts.cdnfonts.com/css/technology" rel="stylesheet">
    </head>
    <body>
        <div class="login-dark">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="form-group"><input class="form-control" type="password" name="password1" placeholder="Password"></div>
            <div class="form-group"><input class="form-control" type="password" name="password2" placeholder="Repite la password"></div>
            <input type="hidden" class="form-control" name="mail" value="<?php echo $mail; ?>">
            <input type="hidden" class="form-control" name="codi_reset" value="<?php echo $code; ?>">
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="chpasswd">Change password</button></div>
            </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    </body>
</html>