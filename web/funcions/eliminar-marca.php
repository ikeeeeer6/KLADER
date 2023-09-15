<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // delete marca i tambe haure de eliminar els productes relacionats amb la marca perque no pot quedar un article sense marca
    $marca_id = intval($_POST["marca"]);
    
    $db->where ("marca_id", $marca_id);
    if($db->delete ('marca')){
        
        $db->where ("article_marca", $marca_id);
        $articles = $db->get('articles');
        foreach($articles as $article){

            $article_id = $article["article_id"];
        
            $db->where ("article_id", $article_id);
            $db->delete ('articles');

            $db->where ("article_id", $article_id);
            $db->delete ('articles_repetits');

            $db->where ("article_id", $article_id);
            $db->delete ('stock');
        
        }
        
        echo 1;

    }else{
        echo 2;
    }

    

?>