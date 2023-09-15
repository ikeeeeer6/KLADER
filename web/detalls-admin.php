<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/detalls-admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>KLÄDER</title>
</head>
<body>

<!-- 

    FUNCIO PRINCIPAL: en aquesta interficie sera on l'admin pot editar els seus articles de la web o eliminarlos, ja que podra cambiar el nom de la marca
                        canviar de categoria o el que sigui.

 -->
    
    <?php include "navbar.php"; 

    if($_SESSION['usuari_id'] == 1 ){

        if( $_GET['id'] != ""){?>

        <!-- POP UPS de alertes -->
        <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up"> </p>
        </div>
        <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up_correcte"> </p>
        </div>

        <div class="div_total_detalls">

            <div class="div_esquerra_detalls">

            <?php
                
                // get de l'article
                $db->where ("article_id", $_GET["id"]);
                $articles = $db->get('articles');
                foreach ($articles as $art){

                    $db->where ("article_id", $_GET["id"]);
                    $articles_repetits = $db->get('articles_repetits');
                    foreach ( $articles_repetits as $art_rep){
                        if ( $art_rep["article_img"] != ""){
                        ?>
                        <!-- agafo img 1 -->
                            <img onclick="obrirDetalls(<?php echo $art_rep['article_id'] ?>)" class="img_div" style="display: none;" id="<?php echo $art_rep["article_id"]?>" src="../imatges/roba/<?php echo $art_rep["article_img"]?>">
                    <?php
                        }
                    }
                    if ( $art["article_img"] != ""){
                        ?>
                        <!-- segona img -->
                            <img onclick="obrirDetalls(<?php echo $art['article_id'] ?>)" class="img_div" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>"> 
                        <?php
                    }
                ?>

                <?php
                }
                ?>

            </div>

            <div class="div_dret_detalls">

                <?php 

                $db->where ("article_id", $_GET["id"]);
                $articles = $db->getOne('articles');

                $db->where ("categoria_id", $articles["article_tipo"]);
                $categoria2 = $db->getOne('categoria');

                $db->where ("marca_id", $articles["article_marca"]);
                $marca2 = $db->getOne('marca');

                ?>

                <!-- tots els detalls de l'article -->

                <h1 style="font: oblique bold 120% cursive; font-size: 40px;">#<?php echo $articles['article_id']?> - <?php echo $categoria2['categoria_nom']?> <?php echo $marca2['marca_nom']?></h1>
                <br>
                <!-- totes les marques -->
                <select name="article_marca" class="inputs_register" style="margin-top: 10px;" id="article_marca" disabled>
                    <?php
                        
                        $marques = $db->get("marca");
                        foreach($marques as $marca){
                            ?>
                                <option id="<?php echo $marca['marca_id']?>_1" value="<?php echo $marca['marca_id']?>_1"><?php echo $marca['marca_nom']?></option>
                            <?php
                        }
                    ?>
                </select><br>
                <!-- totes les categories -->
                <select name="article_tipo" class="inputs_register" style="margin-top: 10px;" id="article_tipo" disabled>
                    <?php       
                        $categories = $db->get("categoria");
                        foreach($categories as $categoria){
                            ?>
                                <option id="<?php echo $categoria['categoria_id'];?>_2" value="<?php echo $categoria['categoria_id']?>"><?php echo $categoria['categoria_nom']?></option>
                            <?php
                        }
                    ?>
                </select>
                <select name="article_roba_o_calçat" style="margin-top: 10px;" class="inputs_register" id="article_roba_o_calçat" disabled>
                            <option value="1_4">Roba</option>
                            <option value="2_4">Calçat</option>
                        </select><br>
                <input type="text" class="inputs_register" id="article_descripcio" value="<?php echo $articles['article_descripcio']?>" placeholder="Descripció" disabled>
                <input type="text" class="inputs_register" id="article_color" value="<?php echo $articles['article_color']?>" placeholder="Color" disabled>
                <select class="inputs_register" name="article_genere" id="article_genere" disabled>
                    <option id="1_3" value="1">Home</option>
                    <option id="2_3" value="2">Dona</option>
                    <option id="3_3" value="3">Unisex</option>
                </select>
                <input type="text" class="inputs_register" id="article_preu" value="<?php echo $articles['article_preu']?>" placeholder="Preu" disabled>
                <br>
                <button class="boton_detalls_admin" onclick="Editar();">Editar</button>
                <button class="boton_detalls_admin" onclick="Guardar(<?php echo $_GET['id']?>);">Guardar</button>
                <button class="boton_detalls_admin" onclick="Eliminar(<?php echo $_GET['id']?>);">Eliminar</button>
                
                <script>
                    // seleccionar en els selects els que son
                    document.getElementById('<?php echo $marca2['marca_id']?>_1').selected = true;
                    document.getElementById('<?php echo $categoria2['categoria_id']?>_2').selected = true;
                    document.getElementById('<?php echo $articles['article_genere']?>_3').selected = true;
                    document.getElementById('<?php echo $articles['article_roba_o_calçat']?>_4').selected = true;
                </script>
            </div>
        </div>
        
        <?php }else { 
            ?> 
            <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: Falta seleccionar els article</h1>
        <?php } ?>

    <?php }else { ?> 
        <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: T'has de identificar per accedir al apartat de Administrador</h1>
    <?php }?>

    <?php include "footer.php"; ?>

</body>
</html>
<script>
    function Editar(){

        // habilitar botons
        document.getElementById("article_marca").disabled = false;
        document.getElementById("article_tipo").disabled = false;
        document.getElementById("article_descripcio").disabled = false;
        document.getElementById("article_genere").disabled = false;
        document.getElementById("article_preu").disabled = false;
        document.getElementById("article_color").disabled = false;
        document.getElementById("article_roba_o_calçat").disabled = false;

    }

    function Guardar(id){

        //fer update de l'article
        var article_marca = $("#article_marca").val();
        var article_tipo = $("#article_tipo").val();
        var article_descripcio = $("#article_descripcio").val();
        var article_color = $("#article_color").val();
        var article_genere = $("#article_genere").val();
        var article_preu = $("#article_preu").val();
        var article_roba_o_calçat = $("#article_roba_o_calçat").val();

        data = {"id":id,"article_marca":article_marca,"article_tipo":article_tipo,"article_descripcio":article_descripcio,"article_color":article_color,"article_genere":article_genere,"article_preu":article_preu,"article_roba_o_calçat":article_roba_o_calçat};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/update-imatge-inputs.php",
            success: function(valores)
            {
                if(valores == 1){
                    // d3esabilitar botons
                    document.getElementById("article_marca").disabled = true;
                    document.getElementById("article_tipo").disabled = true;
                    document.getElementById("article_descripcio").disabled = true;
                    document.getElementById("article_genere").disabled = true;
                    document.getElementById("article_preu").disabled = true;
                    document.getElementById("article_color").disabled = true;

                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: L'article s'ha guardat correctament.";
                    setTimeout('document.location.reload()',1000)

                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: L'article no s'ha guardat correctament.";
                    setTimeout('document.location.reload()',1000)
                }
            }
        })
    }

    function Eliminar(id){

        // eliminar l'article
        data = {"id":id};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/delete-article.php",
            success: function(valores)
            {
                document.getElementById("div_alert_correcte").style.display = "block";
                document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: L'article s'ha eliminat correctament.";
                var myTimeout = setTimeout(redirigir, 3000);

                function redirigir() {
                    window.location.replace("https://daw.institutmontilivi.cat/klader/web/admin.php");
                }

            }
        })
    }
</script>