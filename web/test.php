<?php
//Esta sería la variable que recibiríamos del formulario ya sea por el método POST o GET
$username = 'joseaguilar';

//Si existen espacios vacíos en la cadena mostraremos un error
if (strpos($username, " "))
    echo "Error. La cadena contiene espacios vacíos.";
else
    echo "Correcto. La cadena no contiene espacios vacíos.";
?>