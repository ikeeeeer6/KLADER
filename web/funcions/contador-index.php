<?php

require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

// contador de quan fas click doncs a cadaq article fas un count perque l'admin pugui veure els articles mes vists
$db->where ("article_id", $_POST["valor"]);
$article = $db->getOne('articles');

$article_contador = $article['article_contador'] + 1;

$data = Array (
    "article_contador" => $article_contador
);
$db->where ("article_id", $_POST["valor"]);
$db->update ('articles', $data);
    
?>
