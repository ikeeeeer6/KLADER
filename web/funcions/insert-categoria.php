<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // insert categoria
    if($_POST["categoria"] != ""){
        
        $data = Array (
            "categoria_nom" => $_POST['categoria']
        );
        $db->insert ('categoria', $data);
        
        echo 1;
    
    }else{
        echo 2;
    }
        
?>