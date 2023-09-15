<?php
    require_once ('../MysqliDb.php'); 
    require_once ('../conexio.php');
    session_start();

    if($_SESSION['usuari_id'] == ""){

        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="../style/login.css">
                <title>KLÄDER</title>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            </head>

            <body> 

                <?php include "navbar.php"; ?>

                    <!-- POP UPS de alertes -->
                    <div id="div_alert_login" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
                        El correu ja esta registrat 
                    </div>
                    <div id="div_alert_nom" class="alert alert-danger" style="text-align: center; display:none;" role="alert">
                        <p id="contingut_pop_up"> hjdgf </p>
                    </div>

                    <!-- login i register -->
                    <div class="div_gran" >
                        <div class="div_petit1" id="div_petit1">
                            
                            <h1 class="title_iniciar_sessio">Iniciar Sessió</h1> 

                        <div class="div_inputs">
                                <span>Email</span><br>
                                <input type="email" id="email_login" class="inputs_register"> <br><br>
                                <span>Contrasenya</span><br>
                                <input type="password" id="password_login" class="inputs_register"> <br>

                                <input class="buton_submit_login" type="submit" name="Entrar" id="submit_register" value="Entrar" onclick="PostComprobarLogin();">
                            </div>
                        </div>
                        <div class="div_petit2" id="div_petit2">
                            
                            <h5 class="title_iniciar_sessio2" style="color: black;">No estas registrat?</h5> 

                            <div class="div_inputs">
                                <span>Registrar-se avui mateix per gaudir d'una experiència de compra única.<br><br>Guarda els "looks" que més t'agradin<br>Revisa el teu perfil</span><br><br>
                                <span> No tens compte? <b><a style="cursor: pointer;" onclick="CambiarRegistrarse();">Registra't</a></b></span>
                            </div>
                        </div>

                        <div class="div_petit3" id="div_petit3" style="display: none;">
                            
                            <h1 class="title_iniciar_sessio2" style="color: black;">Avantatges de registrar-te</h1> 

                        <div class="div_inputs">
                                <span>Registrar-se avui mateix per gaudir d'una experiència de compra única.<br><br>Guarda els "looks" que més t'agradin<br>Revisa el teu perfil</span>
                                
                            </div>
                        </div>
                        <div class="div_petit4" id="div_petit4" style="display: none;">
                            
                            <h1 class="title_iniciar_sessio">Resgistrar-se</h1> 

                            <div class="div_inputs">
                                <span>Nom</span><br>
                                <input type="text" id="nom" class="inputs_register" onkeyup="Validar()"> <br>
                                <span>Primer Cognom</span><br>
                                <input type="text" id="cognom1" class="inputs_register" onkeyup="Validar()"> <br>
                                <span>Segon Cognom</span><br>
                                <input type="text" id="cognom2" class="inputs_register" onkeyup="Validar()"> <br>
                                <span>Email</span><br>
                                <input type="email" id="email" class="inputs_register" onkeyup="Validar()"> <br>
                                <span>Contrasenya</span><br>
                                <input type="password" id="contrasenya1" class="inputs_register" onkeyup="Validar()"> <br>
                                <span>Repetir Contrasenya</span><br>
                                <input type="password" id="contrasenya2" class="inputs_register" onkeyup="Validar()"> <br>

                                <input type="submit" name="registrar-se" id="submit_register2" class="buton_submit_login" value="Registrar-se" onclick="PostInsertLogin();" disabled> <br><br>
                                <span> Ja tens compte? <b><a style="cursor: pointer;" onclick="CambiarIniciarSessio();"> Iniciar Sessió</a></b></span>
                            </div>
                        </div>
                    </div>
                <?php include "footer.php"; ?>

            </body>
        </html> 

        <?php
        //depen el sesion va a un lloc o altre
    }else if($_SESSION['usuari_id'] == "1"){
        header('Location: https://daw.institutmontilivi.cat/klader/web/admin.php');
    }else{
        header('Location: https://daw.institutmontilivi.cat/klader/web/perfil.php');
    }

?>

<script>

    function CambiarIniciarSessio(){
        document.getElementById("div_petit1").style.display = "block";
        document.getElementById("div_petit2").style.display = "block";
        document.getElementById("div_petit3").style.display = "none";
        document.getElementById("div_petit4").style.display = "none";
    }

    function CambiarRegistrarse(){
        document.getElementById("div_petit1").style.display = "none";
        document.getElementById("div_petit2").style.display = "none";
        document.getElementById("div_petit3").style.display = "block";
        document.getElementById("div_petit4").style.display = "block";
    }

    function Validar(){

        //patrons de validacio
        var patronNombre = "(^[A-ZÁÉÍÓÚ]{1}([a-zñáéíóú]+){2,})(\s[A-ZÁÉÍÓÚ]{1}([a-zñáéíóú]+){2,})?$";
        
        var nom = document.getElementById("nom").value;
        var cognom1 = document.getElementById("cognom1").value;
        var cognom2 = document.getElementById("cognom2").value;

        var count_nom = 0;
        var count_cognom1 = 0; 
        var count_cognom2 = 0; 
        var count_email = 0; 
        var count_contrasenya = 0;

        if(!nom.match(patronNombre)){
            document.getElementById("div_alert_nom").style.display = "block";
            document.getElementById("div_alert_nom").innerHTML = "ERROR: El nom ha de contenir la primera lletra amb majuscula i cap numero";
            count_nom = 0;
        }
        else{
            document.getElementById("div_alert_nom").style.display = "none";
            count_nom = 1;
        }
        if(count_nom == 1){

            if(!cognom1.match(patronNombre)){
            document.getElementById("div_alert_nom").style.display = "block";
            document.getElementById("div_alert_nom").innerHTML = "El cognom ha de contenir la primera lletra amb majuscula i cap numero ";
            count_cognom1 = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_cognom1 = 1;
            }
        }

        if(count_nom == 1 && count_cognom1 == 1){

            if(!cognom2.match(patronNombre)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "El cognom ha de contenir la primera lletra amb majuscula i cap numero ";
                count_cognom2 = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_cognom2 = 1;
            }
        }

        if(count_nom == 1 && count_cognom1 == 1 && count_cognom2 == 1){
        
            valiCorreu =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            var correu = document.getElementById("email").value;

            if(!correu.match(valiCorreu)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El format del correu es incorrecte ex: xxxxxxx@xxxxx.xx";
                count_email = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                count_email = 1;
            }
        }

        if(count_nom == 1 && count_cognom1 == 1 && count_cognom2 == 1 && count_email == 1 ){

            valiContra= /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,15}/;

            var contrasenya1 = document.getElementById("contrasenya1").value;
            var contrasenya2 = document.getElementById("contrasenya2").value;

            if(!contrasenya1.match(valiContra)){
                document.getElementById("div_alert_nom").style.display = "block";
                document.getElementById("div_alert_nom").innerHTML = "ERROR: El format de la contrasenya ha de contenir majuscules, minuscules, numeros, simbol i entre 8 i 15 caracters";
                count_contrasenya = 0;
            }
            else{
                document.getElementById("div_alert_nom").style.display = "none";
                if(contrasenya1 == contrasenya2){
                    document.getElementById("div_alert_nom").style.display = "none";
                    count_contrasenya = 1;
                }else{
                    document.getElementById("div_alert_nom").style.display = "block";
                    document.getElementById("div_alert_nom").innerHTML = "ERROR: Les contrasenyes no coinsideixen";
                    count_contrasenya = 0;
                }

            }

        }

        if (count_nom == 1 && count_cognom1 == 1 && count_cognom2 == 1 && count_email == 1 && count_contrasenya == 1) {
           
            document.getElementById("submit_register2").disabled = false;
        }
    }

    function PostInsertLogin(){
        
        //agafo camps dels inputs 
        var nom = $("#nom").val();
        var cognom1 = $("#cognom1").val();
        var cognom2 = $("#cognom2").val();
        var email = $("#email").val();
        var contrasenya = $("#contrasenya1").val();

        //post per fer insert de login
        data = {"nom":nom,"cognom1":cognom1,"cognom2":cognom2,"email":email,"contrasenya":contrasenya};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/insert-login.php",
            success: function(valores)
            {
                if(valores == 1){
                    document.getElementById("div_alert_login").style.display = "block";
                }else{
                    window.location.replace("https://daw.institutmontilivi.cat/klader/web/login.php");
                }
            }
        })
    }

    //comprobo que s'hagi registrat anteriorment
    function PostComprobarLogin(){

        var email_login = $("#email_login").val();
        var password_login = $("#password_login").val();

        data = {"email_login":email_login,"password_login":password_login};
        $.ajax({
            type: 'POST', 
            data: data,
            url: "funcions/comprobar-login.php",
            success: function(valores)
            {
                //depenen de la respota va redirigit a un lloc o altre
                var dades = valores.split("|*|");
                var valor = dades[0];
                var usuari_id = dades[1];

                if(valor == 1 && usuari_id == 1){
                    window.location.replace("https://daw.institutmontilivi.cat/klader/web/admin.php");
                }
                else if(valor == 1 && usuari_id != 1){
                    window.location.replace("https://daw.institutmontilivi.cat/klader/web/perfil.php");
                }
                else{
                    document.getElementById("div_alert_login").style.display = "block";
                    document.getElementById("div_alert_login").innerHTML = "ERROR! El correu o la contrasenya no coincideixen amb cap usuari";
                }
            }
        })

    }

</script>