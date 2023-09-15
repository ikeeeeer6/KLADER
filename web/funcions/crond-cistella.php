<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

$var1 = new DateTime("now");

// crond que cada 30 min va mirant la cistella perque si ha un article que supera els 30 minuts se li eliminara de la cistella i despres tambe
// s'eliminara del stock ja que quan esta a la cistella del stock esta com reservat per el client

$cistellas = $db->get("cistella");
foreach($cistellas as $cistella){

    $var2 = new DateTime($cistella['cistella_hora']);
    $total = $var1->format("U") - $var2->format("U");
    if( $total > 1800){ // 30 minuts
        
        $db->where("cistella_id", $cistella['cistella_id']);
        $db->delete ('cistella');

        $data = Array (
            "stock_cistella" => 0
        );

        $db->where("article_id", $cistella['article_id']);
        $db->where("stock_talla",$cistella['cistella_talla']);
        $db->update ('stock', $data);

    }

}
?>
