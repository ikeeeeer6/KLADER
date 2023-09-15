<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/admin.css">
    <title>KLÄDER</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body> 

    <!-- 
        FUNCIO PRINCIPAL: a l'interficie de admin podra controlar tot de la web, podra pujar articles a la web, podra veure els articles penjats i despres
                            tambe podra editarlos o eliminar algun article, tambe pot afegir categories i marques per els nous productes o eliminar la marca o 
                            categoria, despres tenim les estadistiques de la web que veura diferents estadistiques de la web i per ultim podra enviar un email a algun
                            client especial o que compri per oferir-li algun producte
 -->
    
    <?php include "navbar.php"; ?>
    
    <script>
        var imatges_valors = 0;
    </script>

    <?php if($_SESSION['usuari_id'] == 1){?>

        <!-- POP UPS de alertes -->
        <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up"> </p>
        </div>
        <div id="div_alert_correcte" class="alert alert-success" style="text-align: center; display:none;" role="alert">
            <p id="contingut_pop_up_correcte"> </p>
        </div>

        <div class="div_botons">

            <button class="butons_admin" onclick="ContingutPujar();">Pujar</button>
            <button class="butons_admin" onclick="ContingutVisualitzar();">Visualitzar</button>
            <button class="butons_admin" onclick="ContingutMarca();">Marques</button>
            <button class="butons_admin" onclick="ContingutCategoria();">Categories</button>
            <button class="butons_admin" onclick="ContingutEstadistiques();">Estadistiques</button>
            <button class="butons_admin" onclick="ContingutEnvairEmail();">Enviar Email</button>

        </div>

        <!-- div de pujar article -->
        <div class="div_contingut_pujar" id="div_contingut_pujar">

            <form action="admin.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="comprovacio_estat_enviat" id="comprovacio_estat_enviat" value="<?php echo $_POST['comprovacio_estat_enviat']?>">
                <div class="div_pujar_imatge">
                    <div class="div_seleccionar_img">
                        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" onchange="preview_image(event)" id="fileToUpload"> 
                        <input type="file" name="fileToUpload2" id="fileToUpload2" accept="image/*" onchange="preview_image2(event)" id="fileToUpload2"><br>
                    </div>
                    <!-- preview de les imatges pujades -->
                    <div class="imatges_pujades">
                        <img id="output_image"/>
                        <img id="output_image2"/>
                    </div>
                    
                    <div class="div_butons_pujar">
                        <button class="buto_eliminar_file" type="button" id="btneliminar" onclick="eliminar(); preview_image4(event);">Eliminar </button> 
                        <input class="buto_eliminar_file" type="submit" id="input_submit_pujar_img" value="Penjar article" name="submit" onclick="InsertImatges();">
                    </div>
                </div>
                
            
                <div class="div_pujar_imatge_text">

                    <div class="div_pujar_inputs_img">
                        <!-- detalls a escriure de cada articles com la marca, categoria... -->
                        <select name="article_marca" class="inputs_register" style="margin-top: 10px;" id="article_marca">
                            <option value="0">-- Selecciona una Marca --</option>
                            <?php
                                
                                $marques = $db->get("marca");
                                foreach($marques as $marca){
                                    ?>
                                        <option value="<?php echo $marca['marca_id']?>"><?php echo $marca['marca_nom']?></option>
                                    <?php
                                }
                            ?>
                        </select><br>
                        <select name="article_tipo" class="inputs_register" style="margin-top: 10px;" id="article_tipo">
                            <option value="0">-- Selecciona un tipo --</option>
                            <?php
                                
                                $categories = $db->get("categoria");
                                foreach($categories as $categoria){
                                    ?>
                                        <option value="<?php echo $categoria['categoria_id']?>"><?php echo $categoria['categoria_nom']?></option>
                                    <?php
                                }
                            ?>
                        </select><br>
                        <select name="article_roba_o_calçat" style="margin-top: 10px;" class="inputs_register" id="article_roba_o_calçat"><br>
                            <option value="0">-- Selecciona un si es roba o calçat --</option>
                            <option value="1">Roba</option>
                            <option value="2">Calçat</option>
                        </select><br>
                        <input type="text" class="inputs_register" style="margin-top: 10px;" id="article_descripcio" placeholder="Descripció"> <br>
                        <input type="text" class="inputs_register" style="margin-top: 10px;" id="article_color" placeholder="Color"><br>
                        <select name="article_genere" style="margin-top: 10px;" class="inputs_register" id="article_genere"><br>
                            <option value="0">-- Selecciona un gènere --</option>
                            <option value="1">Home</option>
                            <option value="2">Dona</option>
                            <option value="3">Unisex</option>
                        </select><br>
                        <input type="text" class="inputs_register" style="margin-top: 10px;" id="stock_disponible" placeholder="Stock"><br>
                        <input type="text" style="margin-top: 10px;" class="inputs_register" id="article_preu" placeholder="Preu"><br>
                    </div>

                </div>
            </form>       

            <?php 

                // miro que no sigui buit
                if(basename($_FILES["fileToUpload"]["name"]) != "" && basename($_FILES["fileToUpload2"]["name"]) != ""){
                    
                    // pujar la img a la carpeta 
                    $target_dir = "../imatges/roba/";
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $target_file2 = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
                    
                    // agafo la extensio de la img
                    $imageFileType1 = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    $imageFileType2 = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));

                    // nomes poden tenir aquest format
                    if($imageFileType1 == "jpg" || $imageFileType1 == "png" || $imageFileType1 == "jpeg"){

                        if ($imageFileType2 == "jpg" || $imageFileType2 == "png" || $imageFileType2 == "jpeg"){
                            
                            // les penjo a la carpeta
                            $target_dir = "../imatges/roba/";
                            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                            $target_file2 = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
                            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                            move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file2);
                            

                        }else{
                            ?>
                                <script>
                                    document.getElementById("comprovacio_estat_enviat").value = "3";
                                </script>
                            <?php
                        }
                    }
                    else{
                        ?>
                            <script>
                                document.getElementById("comprovacio_estat_enviat").value = "3";
                            </script>
                        <?php
                    }

                }
            
            ?>

        </div>
        <!-- contingut de visualitzar tots els articles -->
        <div class="div_contingut_visualitzar" id="div_contingut_visualitzar" style="display: none;">

            <?php

            $articles = $db->get('articles');
            foreach ($articles as $art){

                $db->where ("article_id", $art["article_id"]);
                $articles_repetits = $db->get('articles_repetits');
                foreach ( $articles_repetits as $art_rep){
                    if ( $art_rep["article_img"] != ""){
                    ?>
                        <img onclick="obrirDetalls(<?php echo $art_rep['article_id'] ?>)" class="img_visualitzar" style="display: none;" id="<?php echo $art_rep["article_id"]?>" src="../imatges/roba/<?php echo $art_rep["article_img"]?>">
                <?php
                    }
                }
                if ( $art["article_img"] != ""){
                    ?>
                        <img onclick="obrirDetalls(<?php echo $art['article_id'] ?>)" class="img_visualitzar" id="<?php echo $art["article_id"]?>" src="../imatges/roba/<?php echo $art["article_img"]?>"> 
                    <?php
                }
            ?>

            <?php
            }
            ?>

        </div>
        
        <!-- add marques o eliminar -->
        <div class="div_contingut_afegir_marca" id="div_contingut_afegir_marca" style="display: none;">
            
            <div class="add_category">
                <h2>Afegeix la marca</h2>
                <p>Escriu en el següent camp la marca que vulguis afegir</p>
                <input class="add_category_input" type="text" id="input_nom_marca" style="width: 65%; margin-top: 40px;"><br>
                
                <button id="enviar_marca_add"  class="btn_add_category" onclick="InsertMarca();">Afegir</button>
            </div>
            <div class="delete_category">
                <h2>Eliminar la marca</h2>
                <p>Selecciona la marca i prem el botó per eliminar-la</p>
                <p><b>A l'eliminar la marca eliminaràs tots els articles relacionats amb aquesta marca*</b></p>
                <select class="delete_category_input" name="article_tipo_marca" style="margin-top: 40px; width: 65%; text-align: center;" id="article_tipo_marca">
             
                <?php
                    // totes les marques    
                    $marques = $db->get("marca");
                    foreach($marques as $marca){
                        ?>
                            <option value="<?php echo $marca['marca_id']?>"><?php echo $marca['marca_nom']?></option>
                        <?php
                    }
                ?>
                </select><br>
                <button id="enviar_marca" class="btn_delete_category" onclick="EliminarMarca();">Eliminar</button>
            </div> 
            </div>

        </div>
        
        <!-- add categories o eliminar i haure de dir si son roba o calçat per tema talles -->
        <div class="div_contingut_afegir_categoria" id="div_contingut_afegir_categoria" style="display: none;">
            
            <div class="add_category">
                <h2>Afegeix la categoria</h2>
                <p>Escriu en el següent camp la categoria que vulguis afegir</p>
                <input class="add_category_input" type="text" id="input_nom_categoria" style="width: 65%; margin-top: 40px;"><br>
                               
                <button id="enviar_categoria"  class="btn_add_category" onclick="InsertCategoria();">Afegir</button>
            </div>
            <div class="delete_category">
                <h2>Eliminar la categoria</h2>
                <p>Selecciona la categoria i prem el botó per eliminar-la</p>
                <p><b>A l'eliminar la categoria eliminaràs tots els articles relacionats amb aquesta categoria*</b></p>
                <select class="delete_category_input" name="article_tipo_categories" style="margin-top: 40px; width: 65%; text-align: center;" id="article_tipo_categories">
             
                <?php
                    //totes les categories  
                    $categories = $db->get("categoria");
                    foreach($categories as $categoria){
                        ?>
                            <option value="<?php echo $categoria['categoria_id']?>"><?php echo $categoria['categoria_nom']?></option>
                        <?php
                    }
                ?>
                </select><br>
                <button id="enviar_categoria" class="btn_delete_category" onclick="EliminarCategoria();">Eliminar</button>
            </div> 
            </div>
            

        </div>
        <!-- estadistiques  -->
        <div class="div_contingut_estadistiques" id="div_contingut_estadistiques" style="display: none;">

            <div class="div_adald_estadistiques">

                <div style="text-align: center;" class="div_esquerre_adald_estadistiques">
                    <!-- mes visites -->
                    <h4 style="background-color: #66dbeb;"> ARTICLES AMB MES VISITES </h4>
                    <table class="table_estadistiques">
                    <tr>
                        <th>Article ID</th>
                        <th>Nom</th>
                        <th>Genere</th>
                        <th>Preu</th>
                        <th>Visites</th>
                    </tr>
                    <!-- get articles desc -->
                    <?php
                        
                        $db->orderBy ("article_contador", "desc");
                        $articles22 = $db->get('articles', 9);
                        foreach($articles22 as $art22){
                            ?>
                            <tr>
                                <td>#<?php echo $art22['article_id']?></td>
                                <td><?php echo $art22['article_compost']?></td>
                                <td><?php echo $art22['article_genere']?></td>
                                <td><?php echo $art22['article_preu']?> €</td>
                                <td><?php echo $art22['article_contador']?></td>
                            </tr>
                            <?php
                        }

                    ?>
                    </table>

                </div>

                <div style="text-align: center;" class="div_dreta_adald_estadistiques">
                    <!-- menys visites -->
                    <h4 style="background-color: #66dbeb;"> ARTICLES AMB MENYS VISITES </h4>
                    <table class="table_estadistiques">
                    <tr>
                        <th>Article ID</th>
                        <th>Nom</th>
                        <th>Genere</th>
                        <th>Preu</th>
                        <th>Visites</th>
                    </tr>
                    <?php
                        
                        $db->orderBy ("article_contador", "asc");
                        $articles222 = $db->get('articles', 9);
                        foreach($articles222 as $art222){
                            ?>
                            <tr>
                                <td>#<?php echo $art222['article_id']?></td>
                                <td><?php echo $art222['article_compost']?></td>
                                <td><?php echo $art222['article_genere']?></td>
                                <td><?php echo $art222['article_preu']?> €</td>
                                <td><?php echo $art222['article_contador']?></td>
                            </tr>
                            <?php
                        }

                    ?>
                    </table>
                </div>

            </div>
            <div class="div_abaix_estadistiques">
                <!-- ultimes compres -->
                <div style="text-align: center;" class="div_esquerre_abaix_estadistiques">
                    <h4 style="background-color: #66dbeb;"> ULTIMES COMPRES </h4>
                    <table class="table_estadistiques">
                        <tr>
                            <th>Compra ID</th>
                            <th>Nom</th>
                            <th>Cognom</th>
                            <th>Població</th>
                            <th>Import</th>
                        </tr>
                        <?php
                            
                            $db->orderBy ("pagament_id", "desc");
                            $pagament = $db->get('pagament', 9);
                            foreach($pagament as $pag){
                                ?>
                                <tr>
                                    <td>#<?php echo $pag['pagament_id']?></td>
                                    <td><?php echo $pag['pagament_nom']?></td>
                                    <td><?php echo $pag['pagament_cognom1']?></td>
                                    <td><?php echo $pag['pagament_poblacio']?></td>
                                    <td><?php echo $pag['pagament_import']?> €</td>
                                </tr>
                                <?php
                            }

                        ?>
                        </table>

                </div>
            
                <div style="text-align: center;" class="div_dreta_abaix_estadistiques">
                    <!-- usuaris registrats -->
                    <h4 style="background-color: #66dbeb;"> USUARIS REGISTRATS </h4>
                    <table class="table_estadistiques" id="table_estadistiques2">
                        <tr>
                            <th>Usuari ID</th>
                            <th>Nom</th>
                            <th>Cognom</th>
                            <th>Correu</th>
                            <th>Telefon</th>
                            <th>Població</th>
                            <th>Codi Postal</th>
                        </tr>
                        <?php
                            
                            $db->where ("usuari_id", 1, ">");
                            $db->orderBy ("usuari_id", "desc");
                            $usuaris = $db->get('usuaris', 9);
                            foreach($usuaris as $usuaris){
                                ?>
                                <tr>
                                    <td>#<?php echo $usuaris['usuari_id']?></td>
                                    <td><?php echo $usuaris['usuari_nom']?></td>
                                    <td><?php echo $usuaris['usuari_cognom']?></td>
                                    <td><?php echo $usuaris['usuari_correu']?></td>
                                    <td><?php echo $usuaris['usuari_telefon']?></td>
                                    <td><?php echo $usuaris['usuari_poblacio']?></td>
                                    <td><?php echo $usuaris['usuari_cp']?></td>
                                </tr>
                                <?php
                            }

                        ?>
                    </table>

                </div>
            </div> 

        </div>
        <!-- enviar un email a algun usuari -->
        <div class="div_contingut_enviar_email" id="div_contingut_enviar_email" style="display: none;">
            
            <div class="div_estadistiques_enviar_correu">
                    
                <h4> A quin correu vols enviar un email? </h4> <br>
                <select name="select_correus" id="select_correus">
                    <?php

                        $db->where ("usuari_id", 1, ">");
                        $usuaris = $db->get('usuaris');
                        foreach ($usuaris as $u){
                    ?>
                        <option value="<?php echo $u['usuari_correu']?>"><?php echo $u['usuari_correu']?></option>
                    <?php 
                        }
                    ?>
                </select>
                <br><br><br>
                <p>Escriu l'assumpte</p>
                <input style="width: 336px;" id="assumpte" type="text">
                <br><br>
                <p>Escriu el missatge</p>
                <textarea name="correu_text" id="correu_text" cols="70" rows="5"></textarea>
                <br><br>
                <button class="btn_enviar_email" onclick="EnviarEmail();">Enviar</button>
            </div>
        </div>
        <?php }else { 
            ?>
            <h1 style="text-align:center; font-size: 25px; margin-top: 25px;"> ERROR: T'has de identificar per accedir al apartat de Administrador</h1>
        <?php }?>
    <?php include "footer.php"; ?>

</body>
</html>

<script>

    var input_valor = document.getElementById("comprovacio_estat_enviat").value;
    
    // pop ups estats
    if(input_valor == "1"){

        document.getElementById("div_alert_nom").style.display = "block";
        document.getElementById("contingut_pop_up").innerHTML = "ERROR: Falten dades per emplenar";

    }
    if(input_valor == "2"){

        document.getElementById("div_alert_correcte").style.display = "block";
        document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: L'article s'ha pujat correctament a la web";
    }
    if(input_valor == "3"){

        document.getElementById("div_alert_nom").style.display = "block";
        document.getElementById("contingut_pop_up").innerHTML = "ERROR: Les imatges han de estar en format jpg, jpeg o png";
    }

    // mostrar i ocultar el div pertanent
    function ContingutPujar(){
        
        document.getElementById("div_contingut_pujar").style.display = "block";
        document.getElementById("div_contingut_visualitzar").style.display = "none";
        document.getElementById("div_contingut_afegir_marca").style.display = "none";
        document.getElementById("div_contingut_afegir_categoria").style.display = "none";
        document.getElementById("div_contingut_estadistiques").style.display = "none";
        document.getElementById("div_contingut_enviar_email").style.display = "none";

    }

    function ContingutVisualitzar(){

        document.getElementById("div_contingut_pujar").style.display = "none";
        document.getElementById("div_contingut_visualitzar").style.display = "block";
        document.getElementById("div_contingut_afegir_marca").style.display = "none";
        document.getElementById("div_contingut_afegir_categoria").style.display = "none";
        document.getElementById("div_contingut_estadistiques").style.display = "none";
        document.getElementById("div_contingut_enviar_email").style.display = "none";

    }

    function ContingutMarca(){

        document.getElementById("div_contingut_pujar").style.display = "none";
        document.getElementById("div_contingut_visualitzar").style.display = "none";
        document.getElementById("div_contingut_afegir_marca").style.display = "block";
        document.getElementById("div_contingut_afegir_categoria").style.display = "none";
        document.getElementById("div_contingut_estadistiques").style.display = "none";
        document.getElementById("div_contingut_enviar_email").style.display = "none";

    }   

    function ContingutCategoria(){

        document.getElementById("div_contingut_pujar").style.display = "none";
        document.getElementById("div_contingut_visualitzar").style.display = "none";
        document.getElementById("div_contingut_afegir_marca").style.display = "none";
        document.getElementById("div_contingut_afegir_categoria").style.display = "block";
        document.getElementById("div_contingut_estadistiques").style.display = "none";
        document.getElementById("div_contingut_enviar_email").style.display = "none";

    }

    function ContingutEstadistiques(){

        document.getElementById("div_contingut_pujar").style.display = "none";
        document.getElementById("div_contingut_visualitzar").style.display = "none";
        document.getElementById("div_contingut_afegir_marca").style.display = "none";
        document.getElementById("div_contingut_afegir_categoria").style.display = "none";
        document.getElementById("div_contingut_estadistiques").style.display = "block";
        document.getElementById("div_contingut_enviar_email").style.display = "none";

    }

    function ContingutEnvairEmail(){

        document.getElementById("div_contingut_pujar").style.display = "none";
        document.getElementById("div_contingut_visualitzar").style.display = "none";
        document.getElementById("div_contingut_afegir_marca").style.display = "none";
        document.getElementById("div_contingut_afegir_categoria").style.display = "none";
        document.getElementById("div_contingut_estadistiques").style.display = "none";
        document.getElementById("div_contingut_enviar_email").style.display = "block";

    }
    // obrir els detalls de l'article
    function obrirDetalls(valor){

        data = {"valor":valor};
        $.ajax({
            type: 'GET', 
            data: data,
            url: "detalls-admin.php?id="+valor,
            success: function(valores)
            {
                //alert(valores);
                var url = "https://daw.institutmontilivi.cat/klader/web/detalls-admin.php?id="+valor;
                window.location.replace(url);
            }
        })
        
    }

    //funcio per eliminar la foto de preview
    function eliminar(){
        document.getElementById("fileToUpload").value= "";
        document.getElementById("fileToUpload2").value= "";
    }
    
    // //funcio per veure la imatge que penja
    function preview_image(event) 
    {
        //crea un objecte
        var reader = new FileReader();
        reader.onload = function()
        {
            var output = document.getElementById('output_image');
            output.src = reader.result;
        }
        //per saber la imatge que li hem passat per el file 
        reader.readAsDataURL(event.target.files[0]);
    }

    //funcio per veure la imatge que penja
    function preview_image2(event) 
    {
        //crea un objecte
        var reader = new FileReader();
        reader.onload = function()
        {
            var output2 = document.getElementById('output_image2');
            output2.src = reader.result;
        }
        //per saber la imatge que li hem passat per el file 
        reader.readAsDataURL(event.target.files[0]);
    }
    //funcio per veure quan treu la imatge
    function preview_image4(event) 
    {
        var output = document.getElementById('output_image');
        output.src = '';
        var output2 = document.getElementById('output_image2');
        output2.src = '';
    }

    function InsertImatges(){

        var article_genere = document.getElementById("article_genere").value;
        var article_tipo = document.getElementById("article_tipo").value;
        var file1 = "";
        var file2 = "";

        // comprobacions que sigui una img i no estigui buid perque el nom de la img me la haig de guardar a la bdd
        if(document.getElementById('fileToUpload').files[0] != undefined){
            
            var file1 = document.getElementById('fileToUpload').files[0]['name'];
            var type1 = document.getElementById('fileToUpload').files[0]['type'];
        }
        if(document.getElementById('fileToUpload2').files[0] != undefined){

            var file2 = document.getElementById('fileToUpload2').files[0]['name'];
            var type2 = document.getElementById('fileToUpload2').files[0]['type'];
        }

        var article_marca = $("#article_marca").val();
        var article_descripcio = $("#article_descripcio").val();
        var article_color = $("#article_color").val();
        var stock_disponible = $("#stock_disponible").val();
        var article_preu = $("#article_preu").val();
        var article_roba_o_calçat = $("#article_roba_o_calçat").val();
        alert(typeof article_roba_o_calçat)
        
        // comprobacions de img / jpg / png
        if(type1 == "image/jpg" || type1 == "image/jpeg" || type1 == "image/png" && type2 == "image/jpg" || type2 == "image/png" || type2 == "image/jpeg"){

            if( file1 != "" && file2 != "" && article_marca != "" && article_tipo != "0" && article_descripcio != "" && article_color != "" && article_genere != "0" && article_preu != "" && stock_disponible != "" && article_roba_o_calçat != "0"){
                
                data = {"article_marca":article_marca,"article_tipo":article_tipo,"article_descripcio":article_descripcio,"article_color":article_color,"article_genere":article_genere,"article_preu":article_preu,"file1":file1,"file2":file2,"stock_disponible":stock_disponible,"article_roba_o_calçat":article_roba_o_calçat};
                $.ajax({
                    type: 'POST', 
                    data: data,
                    url: "funcions/insert-images.php"
                })
                
                document.getElementById("comprovacio_estat_enviat").value = "2";

            }
            else{
                document.getElementById("comprovacio_estat_enviat").value = "1";
            }
        }
        
    }

    function EnviarEmail(){

        // enviar email a l'usuari
        var correu = $("#select_correus").val();
        var assumpte = $("#assumpte").val();
        var text = document.getElementById("correu_text").value;
        
        data = {"correu":correu,"text":text,"assumpte":assumpte};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/enviar-email.php",
            success: function(valores)
            {
                if(valores == 1){
                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: El correu s'ha enviat correctament";
                    setTimeout('document.location.reload()',2000)
                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: Error en l'enviament de correu";
                    setTimeout('document.location.reload()',2000)
                }
                
            }
        })
    
    }

    // insert marca
    function InsertMarca(){

        var input_nom_marca = $("#input_nom_marca").val();

        data = {"marca":input_nom_marca};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/insert-marca.php",
            success: function(valores)
            {

                if(valores == 1){

                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: Marca pujada correctament";
                    setTimeout('document.location.reload()',2000)

                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: El camp no pot estar buit";
                    setTimeout('document.location.reload()',2000)
                }
                
            }
        })

    }

    // delete marca
    function EliminarMarca(){

        var article_tipo_marca = $("#article_tipo_marca").val();

        alert(article_tipo_marca)

        data = {"marca":article_tipo_marca};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/eliminar-marca.php",
            success: function(valores)
            {
                if(valores == 1){
                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: Marca eliminada correctament";
                    setTimeout('document.location.reload()',2000)
                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: Error al eliminar la marca";
                    setTimeout('document.location.reload()',2000)
                }
                
            }
        })

    }

    // insert categoria
    function InsertCategoria(){

        var input_nom_categoria = $("#input_nom_categoria").val();

        data = {"categoria":input_nom_categoria};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/insert-categoria.php",
            success: function(valores)
            {

                if(valores == 1){

                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: Categoria pujada correctament";
                    setTimeout('document.location.reload()',2000)

                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: El camp no pot estar buit";
                    setTimeout('document.location.reload()',2000)
                }
                
            }
        })

    }

    // eliminar categoria
    function EliminarCategoria(){
        
        var article_tipo_categories = $("#article_tipo_categories").val();

        data = {"categoria":article_tipo_categories};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/eliminar-categoria.php",
            success: function(valores)
            {
                if(valores == 1){
                    document.getElementById("div_alert_correcte").style.display = "block";
                    document.getElementById("contingut_pop_up_correcte").innerHTML = "CORRECTE: Categoria eliminada correctament";
                    setTimeout('document.location.reload()',2000)
                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("contingut_pop_up").innerHTML = "ERROR: Error al eliminar la categoria";
                    setTimeout('document.location.reload()',2000)
                }
                
            }
        })

    }

</script>