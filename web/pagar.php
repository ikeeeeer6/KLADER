<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/pagar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>KLÄDER</title>
</head>
<body>
    <?php include "navbar.php";

    // FUNCIO PRINCIPAL: l'usuari podra fer el pagament dels seus articles emplenant amb les seves dades i despres se li enviara un email al seu correu.

    $db->where ("usuari_id", $_SESSION['usuari_id']);
    $cistella = $db->getOne('cistella');

    // miro si tinc session perque nomes poden accedir usuaris registrats 
    if($_SESSION['usuari_id'] != 1 && $cistella['cistella_id'] != ""){?>
    
    <!-- POP UPS de alertes -->
    <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
        <p id="contingut_pop_up"></p>
    </div>
    <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
        <p id="contingut_pop_up_correcte"></p>
    </div>

        <?php
            //contador per tal de saber el preu final de tots els articles que te a la cistella
            $db->where ("usuari_id", $_SESSION['usuari_id']);
            $usuaris = $db->getOne('usuaris');

            $db->where ("usuari_id", $_SESSION['usuari_id']);
            $articles = $db->get('cistella');
            $contador_preu = 0;
            foreach($articles as $art){

                $db->where ("article_id", $art['article_id']);
                $articles2 = $db->getOne('articles');
                
                $contador_preu += $articles2['article_preu'] * $art['cistella_unitats'];
            }
        ?>

        <div class="div_total">
            <div class="div_mig">
                <h1 style="font: oblique bold 120% cursive; font-size: 40px;">PAGAMENT</h1>
                <p style="margin-top: 20px;">En aquest correu s'enviarà un mail amb el resum de la compra</p>
                <input type="email" id="email" style="text-align: center;" class="inputs_pagar" value="<?php echo $usuaris['usuari_correu']?>" disabled><br>
                <input type="text" id="nom" style="margin-top: 20px;" class="inputs_pagar" placeholder="Nom" value="<?php echo $usuaris['usuari_nom']?>"><br>
                <input type="text" id="cognom1" style="margin-top: 20px;" class="inputs_pagar2" placeholder="Primer Cognom" value="<?php echo $usuaris['usuari_cognom']?>">
                <input type="text" id="cognom2" style="margin-top: 20px;" class="inputs_pagar2" placeholder="Segon Cognom" value="<?php echo $usuaris['usuari_cognom2']?>">
                <input type="text" id="direccio" style="margin-top: 20px;" class="inputs_pagar" placeholder="Direcció" value="<?php echo $usuaris['usuari_direccio']?>">
                <input type="text" id="poblacio" style="margin-top: 20px;" class="inputs_pagar" placeholder="Població" value="<?php echo $usuaris['usuari_poblacio']?>">
                <input type="text" id="cp" style="margin-top: 20px;" class="inputs_pagar" placeholder="Codi Postal" value="<?php echo $usuaris['usuari_cp']?>">
                <input type="text" id="numero_targeta" style="margin-top: 20px;" class="inputs_pagar" placeholder="Número de targeta"><br>
                <input type="text" id="datacaducitat" style="margin-top: 20px;" class="inputs_pagar2" placeholder="Data de caducitat">
                <input type="text" id="cvc" style="margin-top: 20px;" class="inputs_pagar2" placeholder="CVC">
                <input type="text" id="nom_targeta" style="margin-top: 20px;" class="inputs_pagar" placeholder="Nom del propietari">
                <p style="font-size: 20px; margin-top: 20px;"> TOTAL: <b><?php echo $contador_preu?> €</p></b>
                
                <button id="btn_pagar" class="btn_pagar" onclick="pagar(<?php echo $_SESSION['usuari_id'];?>, <?php echo $contador_preu?>)">PAGAR</button>
            </div>

        </div>        

        <?php }else { 
            ?> 
            <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: Has de afegir algun producte a la cistella i has hagut de haver iniciat sessió</h1>
        <?php }?>
    <?php include "footer.php";?>
</body>
</html>
<script>

    function pagar(id, preu){

    //agafo totes les dades dels inputs
        var nom = $("#nom").val();
        var cognom1 = $("#cognom1").val();
        var cognom2 = $("#cognom2").val();
        var direccio = $("#direccio").val();
        var poblacio = $("#poblacio").val();
        var cp = $("#cp").val();
        var numero_targeta = $("#numero_targeta").val();
        var datacaducitat = $("#datacaducitat").val();
        var cvc = $("#cvc").val();
        var nom_targeta = $("#nom_targeta").val();
        
        //comprobacions de buit
        if(nom != "" && cognom1 != "" && cognom2 != "" && direccio != "" && poblacio != "" && cp != "" && numero_targeta != "" && datacaducitat != "" && cvc != "" && nom_targeta != ""){
            
            //patrons de validacions
            var patroncvc = "^[0-9]{3,4}$";
            var patronnumero = "^[0-9]{12,16}$";
            var patrodatacaducitat = "^(((1[12])\/22$)|((0[1-9]|1[012])\/(2[3-9]|3[0-9])))$";
            var patronomcognoms = "^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$";

            var count_ntar = 0;
            var count_cvc = 0;
            var count_pro = 0;
            var count_data_caducitat = 0;
            var count_nom_cognoms = 0;
            
            if(!numero_targeta.match(patronnumero)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El numero de targeta nomes pot contenir numeros i ha de tenir entre 12 i 16 digits";
                count_ntar = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_ntar = 1;
            }

            if(count_ntar == 1 ){

                if(!cvc.match(patroncvc)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El cvc ha de contenir 3 o 4 digits";
                count_cvc = 0;
                }
                else{
                    document.getElementById("div_alert_nom").style.display = "none";
                    count_cvc = 1;
                }
            }

            if(count_ntar == 1 && count_cvc == 1){

                if(!datacaducitat.match(patrodatacaducitat)){
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("div_alert_nom").innerHTML = "ERROR: La data de caducitat ha de estar de aquest format: MM/YY i no pot estar caducada";
                    count_data_caducitat = 0;
                }
                else{
                    document.getElementById("div_alert_nom").style.display = "none";
                    count_data_caducitat = 1;
                }
            }

            if(count_ntar == 1 && count_cvc == 1 && count_data_caducitat == 1){

                //faig post per despres enviar un email i tmbe eliminar els articles de la cistella
                document.getElementById("btn_pagar").disabled = true;
                var data = {"usuari_id":id,"pagament_nom":nom,"pagament_cognom1":cognom1,"pagament_cognom2":cognom2,"pagament_direccio":direccio,"pagament_poblacio":poblacio,"pagament_codi_postal":cp,"preu":preu};
                $.ajax({
                    type: 'POST', 
                    data: data ,
                    url: "funcions/pagar.php",
                    success: function(valores)
                    {
                        if(valores == 1){

                            //pop up
                            document.getElementById("div_alert_correcte").style.display = "block";
                            document.getElementById("contingut_pop_up_correcte").innerHTML = "PAGAT CORRECTAMENT: S'ha enviat un email amb el resum de la compra";

                            setTimeout(function(){
                                window.location.replace("https://daw.institutmontilivi.cat/klader/web/index.php");
                            }, 4000);

                        }else{
                            document.getElementById("btn_pagar").disabled = false;
                            document.getElementById("div_alert_nom").style.display = "block";
                            document.getElementById("contingut_pop_up").innerHTML = "ERROR";
                            setTimeout('document.location.reload()',2000)
                        }
                    }
                })
            }
            
        }else{
            document.getElementById("div_alert_nom").style.display = "block";
            document.getElementById("contingut_pop_up").innerHTML = "ERROR: Tots els camps no poden estar buits";
        }
        
    }
</script>
