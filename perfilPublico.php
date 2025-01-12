<?php
session_start();
require("./database/funciones.php");

// Verificar que el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$usuario = obtenerDatosUsuario($user_id);
$imagen_perfil = obtenerImagenUsuario($user_id);

if (isset($_GET['vendedor_id'])) {
    $vendedor_id = $_GET['vendedor_id'];
} else {
    // Si no se recibe el ID, redirigir o mostrar un error.
    echo "Error: No se ha encontrado el ID del vendedor.";
    exit();
}

// Obtener el ID del vendedor desde la sesión
$vendedor_id = $_SESSION['vendedor_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Perfil de Usuario</title>
    <style>
        body {
            background-color: #f4f7fc;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 15vh;
        }

       

     
    </style>
</head>
<body>
   
    <?php include('./views/header.php');?>
  

    <div class="container ">
        <div class="row">
            <div class="col-md-3 d-flex flex-column align-items-center">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <?php if ($imagen_perfil): ?>
                        <img class="profile-image rounded-circle" src="data:image/jpeg;base64,<?= base64_encode($imagen_perfil) ?>" alt="Foto de perfil">
                    <?php else: ?>
                        <img class="profile-image rounded-circle" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
                    <?php endif; ?>
                    <h5 class="mt-3"><?= $usuario->getNombre() . ' ' . $usuario->getApellidos() ?></h5>
                    <p class="text-muted">Usuario</p>
                </div>
            </div>

            <div class="col-md-9">
                <div class="p-3 py-5">
                    <h4>Información del Perfil</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?= $usuario->getNombre() ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Apellidos:</strong> <?= $usuario->getApellidos() ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong> <?= $usuario->getTlf() ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <?= $usuario->getEmail() ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>