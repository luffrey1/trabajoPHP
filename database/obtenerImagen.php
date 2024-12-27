<?php
include("funciones.php");
include("../model/Vehiculo.php");


if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $imagen = obtenerImagenVehiculo($matricula);

    if ($imagen) {
        header("Content-Type: image/jpeg");
        echo $imagen;
    } else {
        // Imagen predeterminada si no se encuentra
        header("Content-Type: image/jpeg");
        echo file_get_contents('default_car.jpg'); // Cambia a la ruta de tu imagen predeterminada
    }
}
?>
