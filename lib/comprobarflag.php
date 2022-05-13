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
    $db = conexionDB();

    $flag=$_POST['flag'];
    $titol=$_POST['titol'];

    if($_POST['flag']){
                $selectcategorias = "SELECT flag FROM reptes WHERE flag = :flag AND titol = :titol";
                $categorias = $db->prepare($selectcategorias);
                $categorias->execute(array(':flag' => $flag, ':titol' => $titol));
                $categorias = $categorias->fetch(PDO::FETCH_ASSOC);
                if($categorias){
                    $selectidrepte = "SELECT idrepte,puntuacio FROM reptes WHERE titol = :titol";
                    $idrepte = $db->prepare($selectidrepte);
                    $idrepte->execute(array(':titol' => $titol));
                    $idrepte = $idrepte->fetch(PDO::FETCH_ASSOC);
                    $puntuacio = (int)$idrepte['puntuacio'];
                    $idrepte = $idrepte['idrepte'];

                    $selectiduser = "SELECT iduser FROM users WHERE username= :user";
                    $iduser = $db->prepare($selectiduser);
                    $iduser->execute(array(':user' => $usuari));
                    $iduser =$iduser->fetch(PDO::FETCH_ASSOC);
                    $iduser = $iduser['iduser'];

                    $selectrepte = "SELECT puntuacio FROM ranking WHERE user= :user";
                    $puntuacio2 = $db->prepare($selectrepte);
                    $puntuacio2->execute(array(':user' => $usuari));
                    $puntuacio2 =$puntuacio2->fetch(PDO::FETCH_ASSOC);
                    $puntuacio2 = (int)$puntuacio2['puntuacio'];

                    if ($puntuacio2){
                        $total=$puntuacio+$puntuacio2;
                        $updateRanking="UPDATE ranking
                        SET puntuacio = :total
                        WHERE user = :user";
                        $updateRanking2=$db->prepare($updateRanking);
                        $updateRanking2->execute(array(':user' => $usuari, ':total' => $total));
                    }
                    else {
                        $insert2 = "INSERT INTO `ranking`(user, puntuacio) VALUES (:user,:puntuacio);";
                        $resultat2 = $db->prepare($insert2);
                        $resultat2->execute(array(':user' => $usuari, ':puntuacio' => $puntuacio));
                    }
                    $insert = "INSERT INTO `valida`(iduser, idrepte) VALUES (:iduser,:idrepte);";
                    $resultat = $db->prepare($insert);
                    $resultat->execute(array(':iduser' => $iduser, ':idrepte' => $idrepte));

                    header("Location:../home.php?flag=1");
                }
                else{
                    header("Location:../home.php?flag=0");
                }
    }