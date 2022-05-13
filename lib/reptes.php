<?php
if((isset($_COOKIE["PHPSESSID"])) && ($_SERVER['REQUEST_METHOD'] === 'POST')){
    session_start();
    if(isset($_SESSION["username"])){
        $usuari = $_SESSION['username'];
    }
    require_once("conectadb.php");
    require_once("funciones.php");
    $db = conexionDB();
    $iduser=selectiduser($usuari);
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
        <nav class="navbar top navbar-dark bg-primary">
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
        <?php
            
        if(isset($_POST['categoria'])){
            $hashtag=$_POST['categoria'];
            $consulta = "SELECT * FROM reptes
            JOIN pertany ON reptes.idrepte = pertany.idrepte
            JOIN categories ON categories.idcategoria = pertany.idcategoria 
            WHERE categories.hashtag = :hashtag";
            $selectreptes = $db->prepare($consulta);
            $selectreptes->execute(array(':hashtag' => $hashtag));
            $totalReptes=totalReptes($hashtag);
            
        }
                    for ($i = 1; $i <= $totalReptes; $i++) {
                        $selectreptes2 = $selectreptes->fetch(PDO::FETCH_ASSOC);
                        $idrepte=$selectreptes2['idrepte'];
                        $cantitatcategories=(int)categoriesRepte($idrepte);
                        $estat=estatRepte($iduser,$idrepte);
                        $arraycategories=arrayCategories($idrepte,$cantitatcategories);
                        mostrarRepte($arraycategories,$selectreptes2,$estat,$i);
                    }
        ?>
    </body>
    <footer>
    <nav class="navbar2 navbar-dark bg-primary">
            <div class="uwu2">
                <a class="boton" href="./home.php" ><img class="imghome" src="../img/home.png"></a>
                <a class="boton" href="./crearepte.php" ><img class="imghome" src="../img/repte.png"></a>
                <a class="boton" href="./userpanel.php" ><img class="imghome" src="../img/user.png"></a>
                <a class="boton" href="./ranking.php" ><img class="imghome" src="../img/ranking.png"></a>
            </div>
        </nav>
    </footer>
</html>
