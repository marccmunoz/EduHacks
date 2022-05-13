<?php

    function updateCuentaActiva($activationCode,$mail){
        require_once("./conectadb.php");
        $db = conexionDB();
        $activationDate = date('Y\/m\/d G:i:s');
        $update = "update users SET active = 1, activationDate = :activationdate WHERE activationCode = :code and mail = :mail";
        $preparada = $db->prepare($update);
        $preparada->execute(array(':activationdate' => $activationDate, ':code' => $activationCode, ':mail' => $mail));
        header("Location:../index.php?error=2");
    }

    updateCuentaActiva($_GET['code'],$_GET['mail']);