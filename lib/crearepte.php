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

    require_once("conectadb.php");

    if(isset($_POST['nourepte'])) {
        if (strlen($_POST['nomrepte']) >= 1 && strlen($_POST['puntuacio']) >= 1 && strlen($_POST['descripcio']) >= 1 && strlen($_POST['flag']) >= 1) {
            $db = conexionDB();
            $nomrepte = trim($_POST['nomrepte']);
            $puntuacio = trim($_POST['puntuacio']);
            $descripcio = trim($_POST['descripcio']);
            $flag = trim($_POST['flag']);
            
            $nombre_base = basename($_FILES["archivo"]["name"]);
            $ruta = "../archivos/" . $nombre_base;
            $subirarchivo = move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta);

                $consulta = "SELECT iduser FROM users WHERE username= :user";
                $iduser = $db->prepare($consulta);
                $iduser->execute(array(':user' => $usuari));
                $iduser =$iduser->fetch(PDO::FETCH_ASSOC);
                $iduser = $iduser['iduser'];
                $datapublicacio = date('Y\/m\/d G:i:s');

                if($subirarchivo){
                    $insert = "INSERT INTO `reptes`(titol, descripcio, puntuacio, flag, dataPublicacio, iduser, ruta, archivo) VALUES (:nomrepte,:descripcio, :puntuacio, :flag, :data, :iduser, :ruta, :archivo);";
                    $resultat = $db->prepare($insert);
                    $resultat->execute(array(':nomrepte' => $nomrepte, ':descripcio' => $descripcio, ':puntuacio' => $puntuacio, ':flag' => $flag, ':ruta' => $ruta, ':archivo' => $nombre_base, ':data' => $datapublicacio, ':iduser' => $iduser));
                }
                else{
                    $insert = "INSERT INTO `reptes`(titol, descripcio, puntuacio, flag, dataPublicacio, iduser) VALUES (:nomrepte,:descripcio, :puntuacio, :flag, :data, :iduser);";
                    $resultat = $db->prepare($insert);
                    $resultat->execute(array(':nomrepte' => $nomrepte, ':descripcio' => $descripcio, ':puntuacio' => $puntuacio, ':flag' => $flag, ':data' => $datapublicacio, ':iduser' => $iduser));
                }

                if($_POST['hashtag']){
                    $array = $_POST['hashtag'];
                    $hashtagarray =  explode ( ' ', $array);
                    for($i = 0; $i < count($hashtagarray); $i++){
                        $sql = 'INSERT INTO `categories`(hashtag) VALUES(:nombre)';
                        $prepare = $db->prepare($sql);
                        $prepare->execute(array(':nombre'=>$hashtagarray[$i]));
                    }
                    $selectultimrepte = "Select idrepte FROM reptes ORDER BY idrepte DESC LIMIT 1";
                    $ultimrepte = $db->query($selectultimrepte);
                    $ultimrepte = $ultimrepte->fetch(PDO::FETCH_ASSOC);
                    $ultimrepte = $ultimrepte['idrepte'];
                    for($j = 0; $j < count($hashtagarray); $j++){
                        $sql2 = 'SELECT idcategoria from categories where hashtag = :hashtag';
                        $idcat = $db->prepare($sql2);
                        $idcat->execute(array(':hashtag'=>$hashtagarray[$j]));
                        $idcat = $idcat->fetch(PDO::FETCH_ASSOC);
                        $idcat = $idcat['idcategoria'];
                        $insert = "INSERT INTO `pertany`(idcategoria, idrepte) VALUES (:cat,:repte);";
                        $resultat = $db->prepare($insert);
                        $resultat->execute(array(':cat' => $idcat, ':repte' => $ultimrepte));
                    }
                }

            if($resultat){
                header("Location: ../home.php?upload=1");
            }
            else{
                header("Location: ./crearepte.php?error=1");
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
    <link rel="icon" type="image/png" href="../img/icon2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="http://fonts.cdnfonts.com/css/technology" rel="stylesheet">
</head>
<body>
    <div class="login-dark">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
            <div class="illustration"><a href="../index.php"><img id="logo" src="../img/Eduhacks2.png" height=”250” width=200></a></div>
            <div class="form-group"><input class="form-control" type="text" name="nomrepte" placeholder="Nom del repte" required></div>
            <div class="form-group"><input class="form-control" type="text" name="puntuacio" placeholder="Puntuacio del repte" required></div>
            <div class="form-group"><textarea name="descripcio" rows="3" class="form-control" type="text" placeholder="Introdueix una descripcio" required></textarea></div>
            <div class="form-group"><input class="form-control" type="text" name="hashtag" placeholder="hashtags"></div>
            <div class="form-group"><input class="form-control" type="file" accept="image/png, image/jpeg" name="archivo"></div>
            <div class="form-group"><input class="form-control" type="text" name="flag" placeholder="Introduce la flag" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="nourepte">Crear Repte</button></div>
            <br>
            <?php
            if(isset($_GET['error'])){
                if($_GET['error']==1){
                    echo "<div class='bad'>Error al introducir datos</div>";
                }
            }
            ?>
            </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>