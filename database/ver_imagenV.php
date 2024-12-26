<?php
session_start();
require("funciones.php");

if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $imagen = obtenerImagenVehiculo($matricula); // Recupera la imagen de la base de datos
if ($imagen) {
    $tipo_imagen = "image/jpeg"; 
    header("Content-Type: " . $tipo_imagen);  
    echo $imagen;
} 
}

?>