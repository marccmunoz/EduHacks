<?php
    if(isset($_POST['mailreset']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require_once("./lib/enviarMail.php");
        require_once("./lib/conectadb.php");
        $db = conexionDB();

        $mailReset=$_POST['mailreset'];

        $sql = "SELECT resetPassCode FROM users WHERE mail = :mail;";
            $resetCode = $db->prepare($sql);
            $resetCode->execute(array(':mail' => $mailReset));
            $resetCode = $resetCode->fetch(PDO::FETCH_ASSOC);
            $resetCode = $resetCode['resetPassCode'];
            EmailRegister($mailReset,$resetCode,0);
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
        <div class="form-group"><input class="form-control" type="username" name="mailreset" placeholder="Email"></div>
        <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="login">Send Reset Password Email</button></div>
        </form>
    </div>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>