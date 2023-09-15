<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

// miro si l'usuari ha donat like abans i si no ha fet like faig delete i sino insert
$db->where("usuari_id", $_POST['usuari_id']);
$db->where("article_id", $_POST['article_id']);
if($db->has("guardats")) {

    $db->where ("usuari_id", $_POST["usuari_id"]);
    $db->where ("article_id", $_POST["article_id"]);
    $db->delete ('guardats', $data);

    echo 2;

}else{
    // insert like
    $data = Array ("usuari_id" => $_POST['usuari_id'],
               "article_id" => $_POST['article_id']
    );
    $db->insert ('guardats', $data);
    
    echo 1;
}

?>