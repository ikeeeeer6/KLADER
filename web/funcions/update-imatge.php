<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    if($_POST['arxiu_1'] && $_POST['arxiu_2']){
        echo "si pots arxiu";
    }else{
        echo "no pots arxiu";
    }


    // $db->orderBy("article_id","desc");
    // $user = $db->getOne ("articles");

    // echo $user["article_id"];
    // $data = Array (
    // "article_img" => $_POST['arxiu_1']
    // );
    // $db->where("article_id",$user["article_id"]);
    // $db->update ('articles', $data);

    // $data = Array ("article_id" => $user["article_id"],
    // "article_img" => $_POST['arxiu_2']
    // );
    // $id = $db->insert ('articles_repetits', $data);

?>