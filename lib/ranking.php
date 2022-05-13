<?php
    if(isset($_COOKIE["PHPSESSID"])){
        session_start();
        if(isset($_SESSION["username"])){
            $usuari = $_SESSION['username'];
        }
    }
    else{
        header("Location:../index.php");
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>EduHacks</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="../img/icon2.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link href="http://fonts.cdnfonts.com/css/sherika" rel="stylesheet">
    </head>
    <header>
        <nav class="navbar navbar-dark bg-primary">
            <img id="logohome" src="../img/Eduhacks2.png">
            <div class="marquee texto"><marquee direction="right"><?php for ($i=0; $i<5; $i++){echo $usuari . ' ';} ?></marquee></div>
            <div class="uwu">
                <a class="boton" href="../home.php" ><img class="imghome" src="../img/home.png"></a>
                <a class="boton" href="./crearepte.php" ><img class="imghome" src="../img/repte.png"></a>
                <a class="boton" href="./userpanel.php" ><img class="imghome" src="../img/user.png"></a>
                <a class="boton" href="./ranking.php" ><img class="imghome" src="../img/ranking.png"></a>
            </div>
        </nav>
    </header>
    <body>
    <div class="login-dark">
    <form action="" method="POST" >
        <?php
        require_once("./conectadb.php");
        require_once("./funciones.php");
        $db = conexionDB();
        $numusers=selectNumUsers();
        $ranking2=ranking();

        echo "<h5 class='form-group texto'>Ranking</h5>";
        for($i=0;$i<$numusers;$i++){
            $ranking = $ranking2->fetch(PDO::FETCH_ASSOC);
            $rankingnumeric = $i + 1;
            if($i==0){
                echo '
                <div class="tabla1">
                    <tr>
                    <div class="ranking1">
                        <td><a>' . $rankingnumeric . ".</a> " . $ranking['user'] . '</td> 
                        <td>' . $ranking['puntuacio'] . '</td>
                    </div>
                    </tr>
                </div>';
            }elseif($i==1){
                echo '
                <div class="tabla2">
                    <tr>
                    <div class="ranking2">
                        <td><a>' . $rankingnumeric . ".</a> " . $ranking['user'] . '</td> 
                        <td>' . $ranking['puntuacio'] . '</td>
                    </div>
                    </tr>
                </div>';
            }elseif($i==2){
                echo '
                <div class="tabla3">
                    <tr>
                    <div class="ranking3">
                        <td><a>' . $rankingnumeric . ".</a> " . $ranking['user'] . '</td> 
                        <td>' . $ranking['puntuacio'] . '</td>
                    </div>
                    </tr>
                </div>';
            }elseif($i>2){
                echo '
                <div class="tabla">
                    <tr>
                    <div class="ranking">
                        <td><a>' . $rankingnumeric . ".</a> " . $ranking['user'] . '</td> 
                        <td>' . $ranking['puntuacio'] . '</td>
                    </div>
                    </tr>
                </div>';
            }
        }
        ?>
    </form>
    </div>
    </body>
    <footer>
    <nav class="navbar2 navbar-dark bg-primary">
            <div class="uwu2">
                <a class="boton" href="../home.php" ><img class="imghome" src="../img/home.png"></a>
                <a class="boton" href="./crearepte.php" ><img class="imghome" src="../img/repte.png"></a>
                <a class="boton" href="./userpanel.php" ><img class="imghome" src="../img/user.png"></a>
                <a class="boton" href="./ranking.php" ><img class="imghome" src="../img/ranking.png"></a>
            </div>
        </nav>
    </footer>
</html>