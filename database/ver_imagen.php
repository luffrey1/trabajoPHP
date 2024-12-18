<?php
session_start();
require("funciones.php");

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $imagen = obtenerImagenUsuario($user_id); // Recupera la imagen de la base de datos
if ($imagen) {
    $tipo_imagen = "image/jpeg"; 
    header("Content-Type: " . $tipo_imagen);  
    echo $imagen;
} else {
    header("Content-Type: image/jpeg");
    echo file_get_contents('1.jpeg'); 

   
}
}

?>