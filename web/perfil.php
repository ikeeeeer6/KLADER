<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/perfil.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>KLÄDER</title>
</head>
<body>

    <?php include "navbar.php"; 

    // FUNCIO PRINCIPAL: l'usuari es podra crear un compte o inciar sessio per despres poder accedir al seu perfil

    // miro si tinc session perque nomes poden accedir usuaris registrats 
    if($_SESSION['usuari_id'] != 1 && $_SESSION['usuari_id'] != ""){ ?>

        <!-- POP UPS de alertes -->
        <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up"> </p>
        </div>
        <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up_correcte"> </p>
        </div>

        <div class="div_botons">

            <button class="butons_perfil" onclick="ContingutDades();">Dades</button>
            <button class="butons_perfil" onclick="ContingutGuardats();">Guardats</button>

        </div>

        <div class="div_perfil_dades" id="div_perfil_dades">

            <?php
                $db->where ('usuari_id', $_SESSION['usuari_id']);
                $usuari = $db->getOne('usuaris');
            ?>

            <h5 style="margin-top: 20px;">Direcció</h5>
            <input type="text" class="inputs_perfil" id="usuari_direccio" value="<?php echo $usuari['usuari_direccio']?>" onkeyup="Validar()" >
            <h5 style="margin-top: 20px;">Població</h5>
            <input type="text" class="inputs_perfil" id="usuari_poblacio" value="<?php echo $usuari['usuari_poblacio']?>" onkeyup="Validar()">
            <h5 style="margin-top: 20px;">Codi Postal</h5>
            <input type="text" class="inputs_perfil" id="usuari_cp" value="<?php echo $usuari['usuari_cp']?>" onkeyup="Validar()">
            <h5 style="margin-top: 20px;">Nom</h5>
            <input type="text" class="inputs_perfil" id="usuari_nom" value="<?php echo $usuari['usuari_nom']?>" onkeyup="Validar()">
            <h5 style="margin-top: 20px;">Primer Cognom</h5>
            <input type="text" class="inputs_perfil" id="usuari_cognom" value="<?php echo $usuari['usuari_cognom']?>" onkeyup="Validar()">
            <h5 style="margin-top: 20px;">Segon Cognom</h5>
            <input type="text" class="inputs_perfil" id="usuari_cognom2" value="<?php echo $usuari['usuari_cognom2']?>" onkeyup="Validar()">
            <h5 style="margin-top: 20px;">Telèfon</h5>
            <input type="text" class="inputs_perfil" id="usuari_telefon" value="<?php echo $usuari['usuari_telefon']?>" onkeyup="Validar()">
            <br>
            
            <button class="btn_editar_perfil" id="btn_submit_perfil" onclick="Guardar(<?php echo $_SESSION['usuari_id']?>);">Guardar</button>
        </div>

        <div class="div_perfil_guardats" id="div_perfil_guardats" style="display:none;">

        <?php

            // imatges guardades de cada usuari que estan guardades a la bdd
            $db->where ("usuari_id", $_SESSION["usuari_id"]);
            $guardats = $db->get('guardats');
            foreach ($guardats as $guar){

                $db->where ("article_id", $guar["article_id"]);
                $articles = $db->get('articles');
                foreach ( $articles as $art){
                    ?>
                        <img class="img_div" onclick="obrirDetalls(<?php echo $art['article_id'] ?>)" onclick="ObrirDetalls(<?php echo $art['article_id'] ?>);" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>">
                    <?php
                }
            }
        ?>

        </div>

    <?php }else { ?> 
        <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: T'has de identificar per accedir al apartat d'usuari</h1>
    <?php }?>

    <?php include "footer.php"; ?>
    
</body>
</html>
<script>

    function ContingutDades(){
        document.getElementById("div_perfil_dades").style.display = "block";
        document.getElementById("div_perfil_guardats").style.display = "none";
    }

    function ContingutGuardats(){
        document.getElementById("div_perfil_dades").style.display = "none";
        document.getElementById("div_perfil_guardats").style.display = "block";
    }

    function Validar(valor){
        
        //patrons de validacions
        var patronNombre = /(^[A-ZÁÉÍÓÚ]{1}([a-zñáéíóú]+){2,})(\s[A-ZÁÉÍÓÚ]{1}([a-zñáéíóú]+){2,})?$/;
        var patronNumero = "^[0-9]+$";
        
        var usuari_poblacio = document.getElementById("usuari_poblacio").value;
        var usuari_cp = document.getElementById("usuari_cp").value;
        var usuari_nom = document.getElementById("usuari_nom").value;
        var usuari_cognom = document.getElementById("usuari_cognom").value;
        var usuari_cognom2 = document.getElementById("usuari_cognom2").value;
        var usuari_telefon = document.getElementById("usuari_telefon").value;

        var count_poblacio = 0;
        var count_cp = 0; 
        var count_nom = 0; 
        var count_cognom1 = 0; 
        var count_contrasenya = 0;
        var count_tlf = 0;

        //validacions
        if(!usuari_poblacio.match(patronNombre)){
            document.getElementById("div_alert_nom").style.display = "block";
            document.getElementById("div_alert_nom").innerHTML = "ERROR: La població ha de contenir la primera lletra amb minuscula i cap numero";
            count_poblacio = 0;
        }
        else{
            document.getElementById("div_alert_nom").style.display = "none";
            count_poblacio = 1;
        }

        if(count_poblacio == 1){

            if(!usuari_cp.match(patronNumero)){
            document.getElementById("div_alert_nom").style.display = "block";
            document.getElementById("div_alert_nom").innerHTML = "ERROR: El codi postal nomes pot contenir numeoros ";
            count_cp = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_cp = 1;
            }
        }
        
        if(count_poblacio == 1 && count_cp == 1){

            if(!usuari_nom.match(patronNombre)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El nom ha de contenir la primera lletra amb minuscula i cap numero";
                count_nom = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_nom = 1;
            }
        }
        if(count_poblacio == 1 && count_cp == 1 && count_nom == 1){

            if(!usuari_cognom.match(patronNombre)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El cognom ha de contenir la primera lletra amb minuscula i cap numero";
                count_cognom1 = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_cognom1 = 1;
            }
        }
        if(count_poblacio == 1 && count_cp == 1 && count_nom == 1 && count_cognom1 == 1){

            if(!usuari_cognom2.match(patronNombre)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El cognom ha de contenir la primera lletra amb minuscula i cap numero";
                count_cognom2 = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_cognom2 = 1;
            }
        }  
        if(count_poblacio == 1 && count_cp == 1 && count_nom == 1 && count_cognom1 == 1 && count_cognom2 == 1){

            if(!usuari_telefon.match(patronNumero)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El telefon nomes pot contenir numeoros";
                count_tlf = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_tlf = 1;
            }
        } 

        if (count_poblacio == 1 && count_cp == 1 && count_nom == 1 && count_cognom1 == 1 && count_cognom2 == 1 && count_tlf == 1) {
            
            document.getElementById("btn_submit_perfil").disabled = false;

        }else{
            document.getElementById("btn_submit_perfil").disabled = true;
        }
    }

    function Guardar(id){

        //agafo totes les dades dels inputs i faig un post a un arxiu per tal desde alla fer update
        var usuari_direccio = $("#usuari_direccio").val();
        var usuari_poblacio = $("#usuari_poblacio").val();
        var usuari_cp = $("#usuari_cp").val();
        var usuari_nom = $("#usuari_nom").val();
        var usuari_cognom= $("#usuari_cognom").val();
        var usuari_cognom2 = $("#usuari_cognom2").val();
        var usuari_telefon = $("#usuari_telefon").val();

        data = {"id":id,"usuari_direccio":usuari_direccio,"usuari_poblacio":usuari_poblacio,"usuari_cp":usuari_cp,"usuari_nom":usuari_nom,"usuari_cognom":usuari_cognom,"usuari_cognom2":usuari_cognom2,"usuari_telefon":usuari_telefon};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/update-dades-perfil.php",
            success: function(valores)
            {
                if(valores == 1){
                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "GUARDAT CORRECTAMENT: Les dades del perfil s'han actualitzat";

                    document.getElementById("usuari_direccio").disabled = true;
                    document.getElementById("usuari_poblacio").disabled = true;
                    document.getElementById("usuari_cp").disabled = true;
                    document.getElementById("usuari_nom").disabled = true;
                    document.getElementById("usuari_cognom").disabled = true;
                    document.getElementById("usuari_cognom2").disabled = true;
                    document.getElementById("usuari_telefon").disabled = true;

                    setTimeout('document.location.reload()',2000)
                }else{
                    // missatge de error
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "GUARDAT CORRECTAMENT: Les dades del perfil s'han actualitzat";
                    setTimeout('document.location.reload()',2000)
                    
                }
            }
        })
    }

    function obrirDetalls(valor){

        //obrir el detalls de cada imatge quan fagi click
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