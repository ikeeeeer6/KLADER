<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // mirar si queden algun article en stock de aquell article perque ens podem quedar sense existencies
    $db->where ("article_id", $_POST['article_id']);
    $db->where ("stock_talla", $_POST['cistella_talla']);
    $stock = $db->getOne('stock');

    $calcul = $stock['stock_comprades'] + $stock['stock_cistella'];
    
    if($stock['stock_disponibles'] <= $calcul){
        echo 2;
    }
    else{
        echo 1;
    }
    
?>