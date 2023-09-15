<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

// miro si tinc de aquest usuari l'article i la talla 
$db->where("usuari_id", $_POST['usuari_id']);
$db->where("article_id", $_POST['article_id']);
$db->where("cistella_talla",$_POST['cistella_talla']);
if($db->has("cistella")) {

    // si el tinc fare un update de la cistella
    $db->where("usuari_id", $_POST['usuari_id']);
    $db->where("article_id", $_POST['article_id']);
    $db->where("cistella_talla",$_POST['cistella_talla']);
    $cistella = $db->getOne("cistella");

    $cistella_unitats = $cistella['cistella_unitats'] + 1;
    
    $data = Array (
        "cistella_unitats" => $cistella_unitats
    );
    
    $db->where("usuari_id", $_POST['usuari_id']);
    $db->where("article_id", $_POST['article_id']);
    $db->where("cistella_talla",$_POST['cistella_talla']);
    $db->update ('cistella', $data);

    // ************** //

    // fare un update de stock
    $db->where("article_id", $_POST['article_id']);
    $db->where("stock_talla",$_POST['cistella_talla']);
    $stock = $db->getOne("stock");

    $stock_cistella = $stock['stock_cistella'] + 1;

    $data2 = Array (
        "stock_cistella" => $stock_cistella
    );

    $db->where("article_id", $_POST['article_id']);
    $db->where("stock_talla",$_POST['cistella_talla']);
    $db->update ('stock', $data2);

}else{

    // si no el tinc doncs fare un insert
    $data = Array (
        "usuari_id" => $_POST['usuari_id'],
        "article_id" => $_POST['article_id'],
        "cistella_talla" => $_POST['cistella_talla'],
        "cistella_hora" => date("H:i:s")
    );
    $db->insert ('cistella', $data);

    // ********* //

    // si no el tinc tambe fare un update perque al stock sempre tinc un valor predeterminat
    $db->where("article_id", $_POST['article_id']);
    $db->where("stock_talla",$_POST['cistella_talla']);
    $stock = $db->getOne("stock");

    $stock_cistella = $stock['stock_cistella'] + 1;

    $data2 = Array (
        "stock_cistella" => $stock_cistella
    );

    $db->where("article_id", $_POST['article_id']);
    $db->where("stock_talla",$_POST['cistella_talla']);
    $db->update ('stock', $data2);
}

echo 1;
?>

