<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');

    // borrar article
    $db->where ("article_id", $_POST["id"]);
    $db->delete ('articles');

    $db->where ("article_id", $_POST["id"]);
    $db->delete ('articles_repetits');

    echo 1;

?>
