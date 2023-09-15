<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

$resultat = 0;

// insert del register i comprobo que no exiteixi
$db->where("usuari_correu",  $_POST['email']);
if($db->has("usuaris")) {

    $resultat = 1;

} else {
    $resultat = 2;
    
    $data = Array ("usuari_correu" => $_POST['email'],
               "usuari_contrasenya" => $_POST['contrasenya'],
               "usuari_nom" => $_POST['nom'],
               "usuari_cognom" => $_POST['cognom1'],
               "usuari_cognom2" => $_POST['cognom2'],
               "usuari_rol" => 2
    );
    $db->insert ('usuaris', $data);
}

echo $resultat;
?>

