<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KLÄDER</title>
    <link rel="stylesheet" href="../style/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <?php 
    
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    
    include "navbar.php"; 
    
    ?>

    <!-- FUNCIO PRINCIPAL: es la pagina principal de la web on pots veure els articles, registar-te, login... -->
    
    <!-- slider principal de la pagina amb 3 imatges -->
    <div class="row">
        <div class="slider-frame" style="transform: scale(0.93);">
            <ul>
                <li><img src="../imatges/balenciaga.jpg" ></li> 
                <li><img src="../imatges/louisvuitton.jpg"></li>
                <li><img src="../imatges/offwhite.jpg"></li>
            </ul>
        </div>
    </div>

    <div class="div_general_articles">

        <div class="div_total_articles">
            <?php

                //get dels 16 primers articles de la web 
                $articles = $db->get('articles', 16);
                foreach ($articles as $art){

                    $db->where ("categoria_id", $art["article_tipo"]);
                    $categoria2 = $db->getOne('categoria');

                    $db->where ("marca_id", $art["article_marca"]);
                    $marca = $db->getOne('marca');

                    ?>
                    <div style="border: 0px solid;" class="div_total_img_for">
                    <div class="div_img_index">
                    <?php

                    $db->where ("article_id", $art["article_id"]);
                    $articles_repetits = $db->get('articles_repetits');
                    foreach ( $articles_repetits as $art_rep){

                        // primera img
                        if ( $art_rep["article_img"] != ""){
                        ?>
                            <img onclick="obrirDetalls(<?php echo $art_rep['article_id'] ?>); contador(<?php echo $art_rep['article_id'] ?>);" class="img_div_no_principal"  id="<?php echo $art_rep["article_id"]?>" src="../imatges/roba/<?php echo $art_rep["article_img"]?>">
                        <?php
                        }
                    }
                    if ( $art["article_img"] != ""){
                        ?>
                            <!-- segona img -->
                            <img onclick="obrirDetalls(<?php echo $art['article_id'] ?>); contador(<?php echo $art_rep['article_id'] ?>);" class="img_div_principal" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>"> 
                        <?php
                    }

                    ?>
                    </div>
                    <!-- text de preus i nom -->
                    <div class="div_sub_text">
                        <h5 class="text_index_titol_roba"><?php echo $categoria2["categoria_nom"] ?> <?php echo $marca["marca_nom"] ?></h5>
                    </div>
                    <div class="div_sub_preu">
                        <h5 class="text_index_titol_preu"><?php echo $art["article_preu"] ?> €</h5>
                    </div>
                    </div>
                    
            <?php
            }
            ?>
        </div>
    </div>
    <?php include "footer.php"; ?>
    
</body>
</html>

<script>

function obrirDetalls(valor){

    // al fer click obro les dades de cada article
    data = {"valor":valor};
    $.ajax({
        type: 'GET', 
        data: data,
        url: "detalls-admin.php?article_id="+valor,
        success: function(valores)
        {
            var url = "https://daw.institutmontilivi.cat/klader/web/detalls-index.php?id="+valor;
            window.location.replace(url);
        }
    })
}

function contador(valor){

    // contador per cada article i es pugui mirar quin article te mes visites
    data = {"valor":valor};
    $.ajax({
        type: 'POST', 
        data: data,
        url: "funcions/contador-index.php"
    })
}

</script>
