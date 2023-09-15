<?php

    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');
    
    $article_tipo = intval($_POST['article_tipo']);
    $article_marca = intval($_POST['article_marca']); 
    $article_roba_o_calçat = intval($_POST['article_roba_o_calçat']); 

    $db->where ("categoria_id", $article_tipo);
    $categoria = $db->getOne ("categoria");

    $db->where ("marca_id", $article_tipo);
    $marca = $db->getOne ("marca");

    $article_compost = $categoria['categoria_nom'] . ' ' . $marca['marca_nom'];
    
    $data = Array (
        "article_marca" => $_POST['article_marca'],
        "article_tipo" => $article_tipo,
        "article_img" => $_POST['file1'],
        "article_descripcio" => $_POST['article_descripcio'],
        "article_color" => $_POST['article_color'],
        "article_genere" => $_POST['article_genere'],
        "article_preu" => $_POST['article_preu'],
        "article_compost" => $article_compost,
        "article_roba_calçat" => $article_roba_o_calçat
    );
    $id = $db->insert ('articles', $data);

    $data2 = Array (
        "article_id" => $id,
        "article_img" => $_POST['file2']
    );
    $db->insert ('articles_repetits', $data2);

    if($article_roba_calçat == 2){
        
        $talla = 35;
        $talla2 = 35.5;

        for($i = 1; $i <= 12; $i++){
            
            $data3 = Array (
                "article_id" => $id,
                "stock_talla" => $talla,
                "stock_disponibles" => $_POST['stock_disponible']
            );
            $db->insert ('stock', $data3);

            $data4 = Array (
                "article_id" => $id,
                "stock_talla" => $talla2,
                "stock_disponibles" => $_POST['stock_disponible']
            );
            $db->insert ('stock', $data4);
            
            $talla++;
            $talla2++;
        }
        
    }else{

        for($i = 1; $i <= 5; $i++){

            if ($i == 1){
                $talla = "XS";
            }elseif ($i == 2){
                $talla = "S";
            }elseif ($i == 3){
                $talla = "M";
            }elseif ($i == 4){
                $talla = "L";
            }elseif ($i == 5){
                $talla = "XL";
            }
            
            $data3 = Array (
                "article_id" => $id,
                "stock_talla" => $talla,
                "stock_disponibles" => $_POST['stock_disponible']
            );
            $db->insert ('stock', $data3);
        }
        
    };
 
?>