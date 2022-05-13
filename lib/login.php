<?php

    if(isset($_SESSION['username'])){
        header("Location:../home.php");
    }
    
    if(isset($_POST['username']) && isset($_POST['password'])){
        require_once("funciones.php");

        login();
    }
?>