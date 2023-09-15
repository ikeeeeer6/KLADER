<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // insert de marca
    if($_POST["marca"] != ""){
        
        $data = Array (
            "marca_nom" => $_POST['marca'],
        );
        $db->insert ('marca', $data);
        
        echo 1;

    }else{
        echo 2;
    }
    
?>