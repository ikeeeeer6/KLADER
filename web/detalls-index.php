<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLÄDER</title>
    <link rel="stylesheet" href="../style/detalls-index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/ee95ac1eee.js" crossorigin="anonymous"></script>
</head>
<body style="padding-bottom: 20px;">
    <input id="usuari_id" type="hidden"><?php echo $_SESSION['usuari_id']?></input>
    <?php include "navbar.php";


// FUNCIO PRINCIPAL: en aquesta interficie l'usuari podra veure tots els detalls de cada article ja que hi posara el preu, les talles, la descripcio..

    if( $_GET['id'] != ""){?>
        
        <!-- POP UPS de alertes -->
        <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up_correcte"> </p>
        </div>
        <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up"> </p>
        </div>

        <div class="div_total">
            
            <div class="div_imatge">

                <?php
                    
                    // get de la img que li paso id per el get
                    $db->where ("article_id", $_GET["id"]);
                    $articles = $db->get('articles');
                    foreach ($articles as $art){

                        ?>
                        <div class="div_img_index2">
                        <?php

                        $db->where ("article_id", $_GET["id"]);
                        $articles_repetits = $db->get('articles_repetits');
                        foreach ( $articles_repetits as $art_rep){
                            if ( $art_rep["article_img"] != ""){
                            ?>
                                <!-- img 1 -->
                                <img onclick="obrirDetalls(<?php echo $art_rep['article_id'] ?>)" class="img_div_no_principal2" id="<?php echo $art_rep["article_id"]?>" src="../imatges/roba/<?php echo $art_rep["article_img"]?>">
                        <?php
                            }
                        }
                        if ( $art["article_img"] != ""){
                            ?>
                            <!-- img 2 -->
                                <img onclick="obrirDetalls(<?php echo $art['article_id'] ?>)" class="img_div_principal2" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>"> 
                            <?php
                        }
                        ?>
                        </div>
                        <?php
                    }
                ?>

            </div>
            <div class="div_info">
                <?php
                    $db->where ("article_id", $_GET["id"]);
                    $articles2 = $db->getOne('articles');

                    $db->where ("categoria_id", $articles2["article_tipo"]);
                    $categoria = $db->getOne('categoria');

                    $db->where ("marca_id", $art["article_marca"]);
                    $marca = $db->getOne('marca');
                ?>
                <!-- montar tota la estructura de preus, descripcio, titul... -->
                <?php $concatenacio = $categoria['categoria_nom'] . " " . $marca['marca_nom']?>
                <div class="div_title">
                    <h1 class="title_img"><b><?php echo $concatenacio; ?></b></h1>
                </div>
                <div class="div_preu">
                    <p><b><?php echo $articles2['article_preu']; ?> € </b></p>
                </div>
                <div class="div_text">
                    <p><?php echo $articles2['article_descripcio']; ?></p>
                </div>
                <div class="div_talles">
                    <br>
                    <?php

                    if($articles2['article_roba_calçat'] == "2"){

                        $m = 35.5;

                        ?>
                        <!-- depenen de article_roba_calçat posarem numeros o talles -->
                        <select id="talla" name="talla">
                            <?php
                        for ($i = 35; $i <= 46; $i++) {
                            ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <option value="<?php echo $m ?>"><?php echo $m ?></option>
                            <?php
                            $m++;
                        }
                        ?>
                        </select> 
                        <?php

                    }else if($articles2['article_roba_calçat'] == "1"){ 
                        ?>
                            <select id="talla" name="talla">
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        <?php
                    }
                    ?>
                </div>
                <?php if($_SESSION['usuari_id']!="" && $_SESSION['usuari_id']!=1){?>
                <div class="div_add">
                    <?php

                        //miro que l'usuari ja li hagi donat like anteriorment per posar una img del like o altre
                        $db->where("usuari_id", $_SESSION['usuari_id']);
                        $db->where("article_id", $art_rep['article_id']);
                        if($db->has("guardats")) {
                            ?>
                                <i class="fa-solid fa-heart btn_detalls_index" onclick="like(<?php echo $art_rep['article_id']?>,<?php echo $_SESSION['usuari_id']?>)"></i>
                            <?php
                        }else{
                            ?>
                                <i class="fa-regular fa-heart" onclick="like(<?php echo $art_rep['article_id']?>,<?php echo $_SESSION['usuari_id']?>)"></i>
                            <?php
                        }
                    ?>

                    <!-- add a la cistella -->
                    <button class="btn_add_bag" onclick="addBag(<?php echo $art_rep['article_id']?>,<?php echo $_SESSION['usuari_id']?>)">AFEGIR CISTELLA</button>
                    <?php 
                    ?>
                </div>
                <?php }?>
                
            </div>

        </div>

    <?php }else { 
        ?> 
        <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: Falta seleccionar els article</h1>
    <?php } ?>

    <?php include "footer.php";?>
</body>
</html>
<script>
    
    function addBag(article_id, usuari_id){

        cistella_talla = document.getElementById("talla").value;

        // primer comprobo disponibilitat de l'article i quan es OK doncs ja podre fer l'insert
        data = {"article_id":article_id,"cistella_talla":cistella_talla};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/comprobar-disponibilitat.php",
            success: function(valores)
            {
                if(valores == 1){

                    cistella_unitats = 1;
                    // insert de l'article a cistella
                    data = {"usuari_id":usuari_id,"article_id":article_id,"cistella_talla":cistella_talla,"cistella_unitats":cistella_unitats};
                    $.ajax({
                        type: 'POST', 
                        data: data,
                        url: "funcions/insert-cistella.php",
                        success: function(valores)
                        {
                            // alert(valores);
                            if(valores == 1){
                                document.getElementById("div_alert_correcte").style.display = "block";
                                document.getElementById("contingut_pop_up_correcte").innerHTML = "AFEGIT CORRECTAMENT: l'article s'ha afegit correctament a la cistella";
                                setTimeout('document.location.reload()',2000);
                            }
                        }
                    })
                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: la talla de aquest article està esgotat";
                    setTimeout('document.location.reload()',2000);          
                }
                
            }
        })

        
    }

    function like(article_id,usuari_id){
        
        // like usuari a article
        data = {"usuari_id":usuari_id,"article_id":article_id};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/insert-like.php",
            success: function(valores)
            {
                location.reload();
            }
        })
    }
</script>