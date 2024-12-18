<?php
session_start();
include("funciones.php");


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $conexion = conectar();
    
    // Recuperamos la imagen desde la base de datos
    $sql = "SELECT foto, tipo_foto FROM Usuario WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($foto_datos, $tipo_foto);
    
    if ($stmt->fetch()) {
        // Si encontramos la imagen, la enviamos al navegador
        header("Content-Type: " . $tipo_foto);
        echo $foto_datos;
    } else {
        // Si no hay imagen, mostramos una por defecto
        header("Content-Type: image/jpeg");
        echo file_get_contents("1.jpeg");
    }
    $stmt->close();
}

?>
