<?php
    if(isset($_COOKIE["PHPSESSID"])){
        session_start();
        if(isset($_SESSION["username"])){
            $usuari = $_SESSION['username'];
        }

        require_once("./lib/conectadb.php");
        require_once("./lib/funciones.php");
        $db = conexionDB();

        $consulta = "SELECT COUNT(idrepte) FROM reptes";
        $reptes = $db->query($consulta);
        $reptes = $reptes->fetch(PDO::FETCH_ASSOC);
        $reptes = $reptes['COUNT(idrepte)'];
    }
    else{
        header("Location:./index.php");
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
        <link rel="icon" type="image/png" href="./img/icon2.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <link href="http://fonts.cdnfonts.com/css/sherika" rel="stylesheet">
    </head>
    <header>
        <nav class="navbar top navbar-dark bg-primary">
            <img id="logohome" src="./img/Eduhacks2.png">
            <div class="marquee texto"><marquee direction="right"><?php for ($i=0; $i<5; $i++){echo $usuari . ' ';} ?></marquee></div>
            <div class="uwu">
                <a class="boton" href="./home.php" ><img class="imghome" src="./img/home.png"></a>
                <a class="boton" href="./lib/crearepte.php" ><img class="imghome" src="./img/repte.png"></a>
                <a class="boton" href="./lib/userpanel.php" ><img class="imghome" src="./img/user.png"></a>
                <a class="boton" href="./lib/ranking.php" ><img class="imghome" src="./img/ranking.png"></a>
            </div>
        </nav>
    </header>
    <body>
    <?php
        if ($reptes>0) //si hay un repte creado
        {   
            $categories=selectCategories();
            $totalCategories=(int)totalCategorias();

        echo "
        <div class='login-dark'>
        <form action='./lib/reptes.php' method='POST'>
        <h6 class='texto'>Selecciona una categoria</h6>
        <select class='select texto2' name='categoria' value='categoria' id='color'>";
            $cat = "";
                for ($i = 1; $i <= $totalCategories; $i++) {
                    $categoria = $categories->fetch(PDO::FETCH_ASSOC);
                    $cat = $categoria['hashtag'];
                    echo "<option name='hastag' value='$cat'>$cat</option>";
            }
        
        echo "</select>";
        if(isset($_GET['flag'])){
            if($_GET['flag']==1){
                echo "
                <br>
                <br>
                <div class='ok'>Flag correcte!</div>";
            }
            else{
                echo "
                <br>
                <br>
                <div class='bad'>Flag incorrecte!</div>";
            }
        }
        echo "
        <div class='form-group'><button class='btn btn-primary btn-block' type='submit' name='buscar'>Buscar</button></div>
        </form>
        </div>";

        }
        else{
            echo "<div class='login-dark'>";
            echo "<form action='' method='POST'>";
            echo "<h6 class='texto'>No hi ha cap repte creat</h6>";
            echo "</form>";
            echo "</div>";
        }
    ?>
    </body>
    <footer>
    <nav class="navbar2 navbar-dark bg-primary">
            <div class="uwu2">
                <a class="boton" href="./home.php" ><img class="imghome" src="./img/home.png"></a>
                <a class="boton" href="./lib/crearepte.php" ><img class="imghome" src="./img/repte.png"></a>
                <a class="boton" href="./lib/userpanel.php" ><img class="imghome" src="./img/user.png"></a>
                <a class="boton" href="./lib/ranking.php" ><img class="imghome" src="./img/ranking.png"></a>
            </div>
        </nav>
    </footer>
</html>
