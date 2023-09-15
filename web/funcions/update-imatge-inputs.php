<?php
require_once ('../../MysqliDb.php'); 
require_once ('../../conexio.php');

// FUNCIÓ PRINCIPAL: actualitzar les dades de la imgh

if($_POST['article_marca'] != "" && $_POST['article_tipo'] != "" && $_POST['article_descripcio'] != "" && $_POST['article_color'] != "" && $_POST['article_genere'] != "" && $_POST['article_preu'] != "" && $_POST['article_roba_o_calçat']){
        
    $article_tipo = intval($_POST['article_tipo']);
    $article_roba_o_calçat = intval($_POST['article_roba_o_calçat']);
    // al article marca li haig de treure el _1 o _2 o _3 per tal de que sem quedi el seu id i poder buscarlo al la base de dades
    $article_marca = intval($_POST['article_marca']);

    // extrec dades per saber marca_id
    $article_marca2 = explode("_", $article_marca);
    $marca_id = $article_marca2[0];

    $db->where ("categoria_id", $article_tipo);
    $categoria = $db->getOne ("categoria");

    $db->where ("marca_id", $marca_id);
    $marca = $db->getOne('marca');

    $article_compost = $categoria['categoria_nom'] . ' ' . $marca['marca_nom'];

    $article_roba_calçat = $categoria["categoria_roba_calçat"];

    $data = Array ("article_marca" => $marca_id,
           "article_tipo" => $_POST['article_tipo'],
           "article_descripcio" => $_POST['article_descripcio'],
           "article_color" => $_POST['article_color'],
           "article_genere" => $_POST['article_genere'],
           "article_preu" => $_POST['article_preu'],
           "article_compost" => $article_compost,
           "article_roba_calçat" => $article_roba_o_calçat
    );
    $db->where ("article_id", $_POST["id"]);
    $db->update ('articles', $data);

    echo 1;
}else{
    echo 2;
}

?>