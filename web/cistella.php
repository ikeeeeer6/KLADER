<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/cistella.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>KLÄDER</title>
</head>
<body>
    <?php include "navbar.php";?>

    <!-- FUNCIO PRINCIPAL: l'usuari podra veure els articles que te a la cistella i els podra eliminar, a mes a mes si vol afegir algun article
                            podra fer clicka  la img per tal de accedir directament al article.
                            
                            Despres tambe tinc un crond que cada 30 minuts mira que l'article afegit a la cistella doncs no sobrepasi de aquest temps
                            perque sin sera eliminat i una altre persona el podra comprar.
    -->

    <!-- POP UPS de alertes -->
    <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
        <p id="contingut_pop_up"> </p>
    </div>
    <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
        <p id="contingut_pop_up_correcte"> </p>
    </div>

        <div class="div_cistella">

            <?php
                $contador_preu = 0;

                // get a la cistella de cada usuari i poder veure totes les seves dades
                $db->where ("usuari_id", $_SESSION['usuari_id']);
                $articles = $db->get('cistella');
                foreach($articles as $art){

                    $db->where ("article_id", $art['article_id']);
                    $articles2 = $db->getOne('articles');

                    $db->where ("categoria_id", $articles2["article_tipo"]);
                    $categoria2 = $db->getOne('categoria');

                    $db->where ("marca_id", $articles2["article_marca"]);
                    $marca = $db->getOne('marca');

                    $contador_preu += $articles2['article_preu'] * $art['cistella_unitats'];
                    ?>
                    <div class="div_article">
                        <div class="div_text">
                            <!-- text de cada article: titol, preu , eliminar, talla, unitats de la talla -->
                            <?php $concatenacio = $categoria2['categoria_nom'] . " " . $marca['marca_nom']?>
                            <h5 style="text-align: center;"><?php echo $concatenacio?></h5>
                            <p style="text-align: center;">Talla: <?php echo $art['cistella_talla']?></p>
                            <p style="text-align: center;">Preu: <?php echo $articles2['article_preu']?> €</p>
                            <p style="text-align: center;">Unitats: <?php echo $art['cistella_unitats']?></p>
                            <button style="float: center;" class="btn_eliminar_cistella" onclick="eliminarProducte(<?php echo $art['cistella_id']?>,<?php echo $art['cistella_unitats']?>,<?php echo $art['article_id']?>,'<?php echo $art['cistella_talla']?>')">Eliminar</button>
                        </div>
                        <div class="div_img">
                            <img src="../imatges/roba/<?php echo $articles2["article_img"]?>" onclick="ObrirIMG(<?php echo $art['article_id'] ?>);" style="width: 150px; height: 150px; cursor: pointer;">
                        </div>
                    </div>  
                    <?php
                }
            ?>
            <br>
            
        </div>
        <div class="div_total_pagar">
            <!-- total a pagar -->
            <p class="text_lletra">TOTAL: <b><?php echo $contador_preu; ?> €</b></p>
            <?php if($contador_preu != 0) {?>
            <a href="pagar.php"><button class="btn_eliminar_cistella"> PAGAR </button></a>
            <?php }?>
        </div>
        
    <?php include "footer.php";?>
</body>
</html>
<script>

    function eliminarProducte(cistella_id, cistella_unitats, article_id, cistella_talla){

        // eliminar el producte de la cistella per talla, id

        data = {"cistella_id":cistella_id,"cistella_unitats":cistella_unitats,"article_id":article_id,"cistella_talla":cistella_talla};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/eliminar-producte-cistella.php",
            success: function(valores)
            {
                if(valores == 1){
                    //location.reload();
                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("div_alert_correcte").innerHTML = "ELIMINAT CORRECTAMENT: L'article s'ha eliminat correctament.";
                    setTimeout('document.location.reload()',1000)
                }else{
                    alert("error")
                }
            }
        })
    }

    
    function ObrirIMG(valor){

        // obrir la img de l'article

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
</script>