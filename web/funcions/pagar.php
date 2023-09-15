<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');
require '../phpmailer/PHPMailerAutoload.php';

// FUNCIO PRINCEPAL: en aquesta funcio rebre quan l'usuari ha pagat enviarli un correu i fer controls de stock

$db->orderBy("pagament_id","desc");
$results = $db->getOne ('pagament');
$pagament_id = $results['pagament_id'] + 1;

// creacio de la seva cistella per posar-ho al email
$db->where("usuari_id",$_POST['usuari_id']);
$cistellas = $db->get ('cistella');
foreach($cistellas as $cistella){

    $db->where("article_id",$cistella['article_id']);
    $articles =  $db->getOne ('articles');
    $text .= $articles['article_compost'] . " ";
    $text .= $cistella['cistella_talla'] . " ";
    $text .= $articles['article_preu'] . " €<br>";
    $text .= "Unitats: ".$cistella['cistella_unitats']. "<br>";
}

$db->where("usuari_id",$_POST['usuari_id']);
$usuaris = $db->getOne ('usuaris');

// ENVIAR EMAIL

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


//agafar el correu de la persona que te session
$mail->AddAddress($usuaris['usuari_correu']);
$mail->Subject = "COMPRA REALITZADA";
$mail->IsHTML(true); 
$mail->Body = "

    <h1 style='text-align: center; color: black;'>Ha realitzat la compra perfectament<h1>
    <hr>
    <p style='font-size: 15px; color: black;'>Hola Sr/a ".$_POST['pagament_nom']." ".$_POST['pagament_cognom1'].",<br>
    Li informem que la seva comanda ha sigut rebuda correctament, el seu codi de reserva es el numero #".$pagament_id."</p>
    <p style='font-size: 15px; color: black;'>
    Dia d'entrega: ".date('d-m-Y', strtotime("+3 day"))." <br><br>

    Resum de la compra: <br>".$text."
    <br>
    Preu final: ". $_POST['preu']." €
    </p>
    

";

// si el mail de ha enviat
if ($mail->Send()) {

    //add a pagament
    $data = Array (
        "pagament_direccio" => $_POST['pagament_direccio'],
        "pagament_poblacio" => $_POST['pagament_poblacio'],
        "pagament_nom" => $_POST['pagament_nom'],
        "pagament_cognom1" => $_POST['pagament_cognom1'],
        "pagament_cognom2" => $_POST['pagament_cognom2'],
        "pagament_codi_postal" => $_POST['pagament_codi_postal'],
        "usuari_id" => $_POST['usuari_id'],
        "pagament_import" => $_POST['preu']
    );
    $db->insert ('pagament', $data);
    
    // control de stock per tal de que em baixi si ha fet la compra correctament
    foreach($cistellas as $cistella){
        
        $db->where ('article_id', $cistella['article_id']);
        $db->where ('stock_talla', $cistella['cistella_talla']);
        $stock = $db->getOne('stock');

        $stock_cistella = $stock['stock_cistella'] - $cistella['cistella_unitats'];

        $data2 = Array (
            "stock_comprades" => $cistella['cistella_unitats'],
            "stock_cistella" => $stock_cistella
        );

        $db->where ('article_id', $cistella['article_id']);
        $db->where ('stock_talla', $cistella['cistella_talla']);
        $db->update ('stock', $data2);
    }

    // borrar la cistella de l'usuari
    $db->where ('usuari_id', $_POST['usuari_id']);
    $db->delete ('cistella');
    
    echo 1;

} else {
    echo 2;
}
?>