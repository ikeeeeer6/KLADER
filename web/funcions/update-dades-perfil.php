<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

// FUNCIO PRINCIPAL: modicar les dades del usuari
$data = Array (
        "usuari_direccio" => $_POST['usuari_direccio'],
        "usuari_poblacio" => $_POST['usuari_poblacio'],
        "usuari_cp" => $_POST['usuari_cp'],
        "usuari_nom" => $_POST['usuari_nom'],
        "usuari_cognom" => $_POST['usuari_cognom'],
        "usuari_cognom2" => $_POST['usuari_cognom2'],
        "usuari_telefon" => $_POST['usuari_telefon']
);
$db->where ("usuari_id", $_POST["id"]);
$db->update ('usuaris', $data);

echo 1;
?>