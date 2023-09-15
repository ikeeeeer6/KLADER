<?php
    require_once ('../../MysqliDb.php'); 
    require_once ('../../conexio.php');
    session_start();

    $resultat = 0;

    // comprobar de que l'usuari posi el correu i la contrasenya correcte per tal de accedir al perfil
    $db->where("usuari_correu",  $_POST['email_login']);
    $db->where("usuari_contrasenya",  $_POST['password_login']);
    if($db->has("usuaris")) {

        $db->where("usuari_correu",  $_POST['email_login']);
        $db->where("usuari_contrasenya",  $_POST['password_login']);
        if($user = $db->getOne("usuaris")) {
            
            $usuari_id = $user['usuari_id'];
            $_SESSION["usuari_id"] = $usuari_id;
            $resultat = 1;
        }
    } else {
        $resultat = 2;
        
    }

    $resultat .= '|*|'.$usuari_id;

    echo $resultat;
?>