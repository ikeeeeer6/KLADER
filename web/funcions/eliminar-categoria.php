<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // delete categoria i tambe haure de eliminar els productes relacionats amb la categoria perque no pot quedar un article sense categoria

    $db->where ("categoria_id", $_POST["categoria"]);
    if($db->delete ('categoria')){
        
        $db->where ("article_tipo", $_POST["categoria"]);
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