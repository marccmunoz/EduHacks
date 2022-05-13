<?php

function login(){
 
        require_once("conectadb.php");
        $db = conexionDB();
        $usuariLogin = $_POST['username'];
        $mailLogin = $_POST['username'];
        $passwordLogin = $_POST['password'];
        $active = 1;
        if(strpos($mailLogin, '@') !== false){
            $sql2 = "SELECT mail,passHash,username FROM users WHERE active = :active;";
            $mails = $db->prepare($sql2);
            $mails->execute(array(':active' => $active));
        }else{
            $sql = "SELECT username,passHash FROM users WHERE active = :active;";
            $usuaris = $db->prepare($sql);
            $usuaris->execute(array(':active' => $active));
        }
        
        if(isset($usuaris)){

            $existeix = false;
            foreach ($usuaris as $fila){
                if($usuariLogin == $fila[0] && password_verify($passwordLogin, $fila[1])){
                    $existeix = true;
                    $_SESSION['username'] = $usuariLogin;
                    $_SESSION['password'] = $passwordLogin;
                    $ultimlogin = date('Y\/m\/d G:i:s');
                    $update = "update users SET lastSignIn = :a WHERE username = :b ;";
                    $preparada = $db->prepare($update);
                    $preparada->execute(array(':a' => $ultimlogin, ':b' => $usuariLogin));
                    break;
                }
            }
        }
        
        if(isset($mails)){
            $existeix = false;
            foreach ($mails as $fila){
                if($mailLogin == $fila[0] && password_verify($passwordLogin, $fila[1])){
                    $usuariLogin = $fila[2];
                    $existeix = true;
                    $_SESSION['username'] = $usuariLogin;
                    $_SESSION['password'] = $passwordLogin;
                    $ultimlogin = date('Y\/m\/d G:i:s');
                    $update = "update users SET lastSignIn = :a WHERE username = :b ;";
                    $preparada = $db->prepare($update);
                    $preparada->execute(array(':a' => $ultimlogin, ':b' => $usuariLogin));
                    break;
                }
            }
        }

        if($existeix == true){
            session_start();
            $_SESSION['username'] = $usuariLogin;
            header("Location:../home.php");
        }
        else{
            header("Location:../index.php?error=1");
        }
}

function mostrarRepte($arraycategories,$selectreptes2,$estat,$i){
        require_once("conectadb.php");
        $db = conexionDB();

        $titol=$selectreptes2['titol'];
        echo "<div class='login-dark'>
        <a href='#scroll".($i-1)."'><img class='flecha1'src='../img/up.png'></a>
        <form action='./comprobarflag.php' method='POST'>
        <div id='scroll".$i."'>
            <div class='titol linia'>" . $selectreptes2['titol'] . "</div>" . "<div class='puntos linia'>" . $selectreptes2['puntuacio'] . " pts</div>
           </div>
           <br>
           $arraycategories
           <br>
           <br>
           <h5 class='form-group texto'>Description</h5>
           <h6>" . $selectreptes2['descripcio'] . "</h6>";
           if ($selectreptes2['ruta']){
            echo "
            <h5 class='form-group texto'>Additional Resources</h5>
            <a class='descarga' href='" . $selectreptes2['ruta'] . "'download='archivo'>". $selectreptes2['archivo'] . "<img class='descarga2' src='../img/descarga.png' alt='download'></a>";
            echo "<br>";
            }
           echo "
           <br>
           <input type='hidden' name='titol' value='".$titol."'/>";
           if ($estat==false){
                echo "<div class='form-group'><input class='form-control' type='text' name='flag' placeholder='flag' required></div>";
                echo "<div class='form-group'><button class='btn btn-primary btn-block' type='submit' name='register'>Check Flag!</button></div>";
           }
           else{
               echo "<div class='ok'>Ya has realitzat aquest repte</div>";
           }
           echo "
           </form>
           <a href='#scroll".($i+1)."'><img class='flecha2'src='../img/down.png'></a>
           </div>";
    }


    function selectCategories(){
        require_once("conectadb.php");
        $db = conexionDB();
        $consulta = "SELECT hashtag FROM categories";
        $categories = $db->query($consulta);
        return $categories;
    }

    function totalCategorias(){
        require_once("conectadb.php");
        $db = conexionDB();
        $consulta = "SELECT count(hashtag) FROM categories";
        $totalCategorias = $db->query($consulta);
        $totalCategorias2 = $totalCategorias->fetch(PDO::FETCH_ASSOC);
        $total = $totalCategorias2['count(hashtag)'];
        return $total;
    }

    function categoriesRepte($idrepte){
        require_once("conectadb.php");
        $db = conexionDB();
        $selectcountcategories = "SELECT count(categories.hashtag) as cantitat
                FROM pertany JOIN categories
                ON categories.idcategoria = pertany.idcategoria
                INNER JOIN reptes
                ON reptes.idrepte = pertany.idrepte
                WHERE reptes.idrepte = '$idrepte'";
                $countcategories2 = $db->prepare($selectcountcategories);
                $countcategories2->execute();
                $countcategories = $countcategories2->fetch(PDO::FETCH_ASSOC);
                $cantitatcategories = $countcategories['cantitat'];
        return $cantitatcategories;
    }

    function arrayCategories($idrepte,$cantitatcategories){
        require_once("conectadb.php");
        $db = conexionDB();
        $selectcategoria = "SELECT reptes.idrepte, categories.idcategoria, categories.hashtag as categoria
                FROM pertany JOIN categories
                ON categories.idcategoria = pertany.idcategoria
                JOIN reptes
                ON reptes.idrepte = pertany.idrepte
                WHERE reptes.idrepte = '$idrepte'";
                $categoria = $db->prepare($selectcategoria);
                $categoria->execute();
                
                $categories = "";
                for ($i = 1; $i <= $cantitatcategories; $i++) {
                    $categoria2 = $categoria->fetch(PDO::FETCH_ASSOC);
                    $categories = $categories . " #" . $categoria2['categoria'];
                }
        return $categories;
    }

    function totalReptes($hashtag){
        require_once("conectadb.php");
        $db = conexionDB();
        $consulta = "SELECT count(titol) FROM reptes
            JOIN pertany ON reptes.idrepte = pertany.idrepte
            JOIN categories ON categories.idcategoria = pertany.idcategoria 
            WHERE categories.hashtag = :hashtag";
            $selectreptes = $db->prepare($consulta);
            $selectreptes->execute(array(':hashtag' => $hashtag));
            $selectreptes2 = $selectreptes->fetch(PDO::FETCH_ASSOC);
            $totalReptes=$selectreptes2['count(titol)'];
            return $totalReptes;
    }

    function estatRepte($iduser,$idrepte){
        require_once("conectadb.php");
        $db = conexionDB();
        $consultacompletat = "SELECT idrepte, iduser FROM valida WHERE idrepte = $idrepte AND iduser = $iduser";
                $consultacompletat2 = $db->prepare($consultacompletat);
                $consultacompletat2->execute();
                $consultacompletat = $consultacompletat2->fetch(PDO::FETCH_ASSOC);
        if($consultacompletat){
            $estat=true;
        }
        else{
            $estat=false;
        }
        return $estat;
    }

    function selectiduser($usuari){
        require_once("conectadb.php");
        $db = conexionDB();
        $selectiduser = "SELECT iduser FROM users WHERE username= :user";
                    $iduser = $db->prepare($selectiduser);
                    $iduser->execute(array(':user' => $usuari));
                    $iduser =$iduser->fetch(PDO::FETCH_ASSOC);
                    $iduser = $iduser['iduser'];
        return $iduser;
    }

    function selectNumUsers(){
        require_once("conectadb.php");
        $db = conexionDB();
        $count = "SELECT COUNT(user) FROM ranking";
        $count = $db->prepare($count);
        $count->execute();
        $numusers2 = $count->fetch(PDO::FETCH_ASSOC);
        $numusers = $numusers2['COUNT(user)'];
        return $numusers;
    }

    function ranking(){
        require_once("conectadb.php");
        $db = conexionDB();
        $select = "SELECT user, puntuacio FROM ranking ORDER BY 2 DESC, 2;";
        $ranking2 = $db->prepare($select);
        $ranking2->execute();
        return $ranking2;
    }

    function selectMeuReptes($iduser,$cantitatreptes){
        require_once("conectadb.php");
        $db = conexionDB();
        $selectrepte = "SELECT titol FROM reptes WHERE iduser = :iduser";
                $repte = $db->prepare($selectrepte);
                $repte->execute(array(':iduser' => $iduser));
                

                $reptes = "";
                for ($i = 1; $i <= $cantitatreptes; $i++) {
                    $repte2 = $repte->fetch(PDO::FETCH_ASSOC);
                    $reptes = $reptes . "<br>" . $repte2['titol'];
                }
        return $reptes;
    }

    function selectcantitatreptes($iduser){
        require_once("conectadb.php");
        $db = conexionDB();
        $selectrepte = "SELECT count(titol) FROM reptes WHERE iduser = :iduser";
                $repte = $db->prepare($selectrepte);
                $repte->execute(array(':iduser' => $iduser));
                $numreptes = $repte->fetch(PDO::FETCH_ASSOC);
                $numreptes = $numreptes['count(titol)'];
        return $numreptes;
    }

?>