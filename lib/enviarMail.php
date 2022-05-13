<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

function EmailRegister($email,$code,$id) {

$mail = new PHPMailer();
$mail->IsSMTP();

if($id==1){
    $mailbody = '<h1>Educhacks</h1>
        <h3>Hey Buenas!</h3>
        <h4>Da click en el enlace para activar tu cuenta capo</h4>
        <a href="http://localhost/lib/mailcheckAc.php?code='.$code.'&mail='.$email.'">Active yor account Now!</a>';
}else{
    $mailbody = '<h1>Educhacks</h1>
            <h3>Hey Buenas!</h3>
            <h4>Haz click en el enlace de abajo para cambiar la contraseña:</h4>
            <a href="http://localhost/lib/resetPassword.php?code='.$code.'&mail='.$email.'">Reset Password</a>';
}

//Configuració del servidor de Correu
//Modificar a 0 per eliminar msg error
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;

//Credencials del compte GMAIL
$mail->Username = '';
$mail->Password = '';

//Dades del correu electrònic
$mail->SetFrom('accounts@eduhacks.com','Eduhacks');
$mail->Subject = 'Gestion de cuentas EduHacks';
$mail->MsgHTML($mailbody);
//$mail->addAttachment("fitxer.pdf");

//Destinatari
$address = $email;
$mail->AddAddress($address, 'Hola');

//Enviament
$result = $mail->Send();
if(!$result){
    echo 'Error: ' . $mail->ErrorInfo;
}
}

function codigoVerificacion(){

$value = 'Eduhacks'.rand(10,10000);
$randomHash = hash('sha256',$value);

return $randomHash;
}