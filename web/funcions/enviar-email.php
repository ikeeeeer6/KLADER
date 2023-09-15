<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');
require '../phpmailer/PHPMailerAutoload.php';

// enviar un email a un usuari en concret

//Create a new PHPMailer instance
$mail = new PHPMailer();
$mail->IsSMTP();
 
//Configuracion servidor mail
$mail->From = "ikertaliga@gmail.com"; //remitente
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls'; //seguridad
$mail->Host = "smtp.gmail.com"; // servidor smtp
$mail->Port = 587; //puerto
$mail->Username ='a2006427@institutmontilivi.cat'; //nombre usuario
$mail->Password = 'ip413664'; //contraseña
//Agregar destinatario
$mail->AddAddress($_POST['correu']);
$mail->Subject = $_POST['assumpte'];
$mail->IsHTML(true); 
$mail->Body = $_POST['text'];

//Avisar si fue enviado o no y dirigir al index
if ($mail->Send()) {
    echo 1;
} else {
    echo 2;
}
?>