<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/buscador.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>KLÄDER</title>
</head>
<body>
    <?php include "navbar.php"; ?>

    <!-- FUNCIO PRINCIPAL: en aquesta interficie doncs tindre el que es el buscador que cada cop que algun busqui una paraula podra accedir a la paraula buscada 
                            i es buscara desde la base de dades i es mostrara els articles relacionats.

    -->
    
    <div class="div_general_articles">

        <div class="div_total_articles">
            <?php

                // recupero el get que alla tinc la paraula buscada i el sexe si es el cas del menu 
                $genere = explode("_", $_GET['b']);
                
                $gen2 = $genere[2];
                $gen = $genere[1];
                $valor = $genere[0];

                $buscador = '%'.$valor.'%';

                $db->where ("article_compost", $buscador, 'like');
                if( $genere[1] == "1" || $genere[1] == "2" || $genere[1] == "3"){
                    // miro que tingui dos id 
                    $db->where('article_genere', Array($gen, $gen2), 'IN');
                }
                $articles = $db->get('articles');
                if(empty($articles)){
                    ?>
                    <div class="div_total_buscador_no_trobat">
                        <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> NO S'HA TROBAT CAP ARTICLE</h1>
                    </div>
                    <?php
                }
                foreach ($articles as $art){

                    // mostro els articles 
                    $db->where ("categoria_id", $art["article_tipo"]);
                    $categoria2 = $db->getOne('categoria');

                    $db->where ("marca_id", $art["article_marca"]);
                    $marca = $db->getOne('marca');

                    ?>
                    <div class="div_total_img_for">
                        <div class="div_img_index">
                            
                            <?php
                            $db->where ("article_id", $art["article_id"]);
                            $articles_repetits = $db->get('articles_repetits');
                            foreach ( $articles_repetits as $art_rep){

                                if ( $art_rep["article_img"] != ""){
                                ?>
                                    <!-- mig 1 -->
                                    <img onclick="obrirDetalls(<?php echo $art_rep['article_id'] ?>)" class="img_div_no_principal"  id="<?php echo $art_rep["article_id"]?>" src="../imatges/roba/<?php echo $art_rep["article_img"]?>">
                            <?php
                                }
                            }
                            if ( $art["article_img"] != ""){
                                ?>
                                    <!-- img 2 -->
                                    <img onclick="obrirDetalls(<?php echo $art['article_id'] ?>)" class="img_div_principal" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>"> 
                                <?php
                            }
                            ?>
                            
                        </div>
                        <div class="div_sub_text">
                            <h5 class="text_index_titol_roba"><?php echo $categoria2["categoria_nom"] ?> <?php echo $marca["marca_nom"] ?></h5>
                        </div>
                        <div class="div_sub_preu">
                            <h5 class="text_index_titol_preu"><?php echo $art["article_preu"] ?> €</h5>
                        </div>
                    </div>
                    <?php
                    ?>
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

        // obro detalls de cada article
        data = {"valor":valor};
        $.ajax({
            type: 'GET', 
            data: data,
            url: "detalls-admin.php?article_id="+valor,
            success: function(valores)
            {
                //alert(valores);
                var url = "https://daw.institutmontilivi.cat/klader/web/detalls-index.php?id="+valor;
                window.location.replace(url);
            }
        })
    }
</script>