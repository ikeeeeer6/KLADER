<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');
    
    // eliminar productes de la cistella
    $db->where ("cistella_id", $_POST["cistella_id"]);
    if($db->delete ('cistella')){

        $db->where("article_id", $_POST['article_id']);
        $db->where("stock_talla",$_POST['cistella_talla']);
        $stock = $db->getOne("stock");

        $stock_cistella = $stock['stock_cistella'] - $_POST['cistella_unitats'];

        // despres haure de pujar l'estock perque sempre es reserva quan el te a la cistella 
        $data2 = Array (
            "stock_cistella" => $stock_cistella
        );

        $db->where("article_id", $_POST['article_id']);
        $db->where("stock_talla",$_POST['cistella_talla']);
        $db->update ('stock', $data2);

        echo 1;
    }else{
        echo 2;
    }
    
?>
