<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';
$ajustesPath = '/trabajoPHP/perfil/ajustes.php';
$vehiculos = '/trabajoPHP/perfil/vehiculos.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /trabajoPHP/inicio/login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];




$usuario = obtenerDatosUsuario( $user_id);
$imagen_perfil = obtenerImagenUsuario($user_id); 

if (!$usuario) {
    echo "No se han encontrado datos del usuario.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <title>Mi Perfil</title>
   <style>
       .profile-card {
           background-color: #f8f9fa;
           border-radius: 10px;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           padding: 30px;
           margin-top: 30px;
       }
       .profile-image {
           border-radius: 50%;
           border: 5px solid #e0e0e0;
           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
       }
       .profile-header {
           font-weight: bold;
           font-size: 1.2rem;
       }
       .profile-section {
           margin-bottom: 15px;
       }

       .misV{
        width: 60%;
       }
   </style>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/header.php'; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <div class="text-center mb-4">
                    <h4>Mi Perfil</h4>
                    <p class="text-muted">Aquí puedes ver tus datos personales</p>
                </div>
                <div class="row">
                    <!-- Imagen de perfil -->
                    <div class="col-md-4 text-center">
                        <?php if ($imagen_perfil): ?>
                            <img class="profile-image" width="150px" src="data:image/jpeg;base64,<?= base64_encode($imagen_perfil) ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <img class="profile-image" width="150px" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
                        <?php endif; ?>
                    </div>

                    <!-- Datos del perfil -->
                    <div class="col-md-8">
                        <div class="profile-section">
                            <p class="profile-header">ID de Usuario:</p>
                            <p><?= $usuario->getId() ?></p>
                        </div>
                        <div class="profile-section">
                            <p class="profile-header">Nombre:</p>
                            <p><?= $usuario->getNombre() ?> <?= $usuario->getApellidos() ?></p>
                        </div>
                        <div class="profile-section">
                            <p class="profile-header">Correo Electrónico:</p>
                            <p><?= $usuario->getEmail() ?></p>
                        </div>
                        <div class="profile-section">
                            <p class="profile-header">Teléfono:</p>
                            <p><?= $usuario->getTlf() ?></p>
                        </div>
                        <div class="profile-section">
                            <p class="profile-header">Dirección:</p>
                            <p><?= $usuario->getDireccion() ?></p>
                        </div>
                        <div class="profile-section">
                            <p class="profile-header">Código Postal:</p>
                            <p><?= $usuario->getCp() ?></p>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center mt-4">
                    <a href="ajustes.php" class="btn btn-warning">Editar Perfil</a>
                </div>

            </div>
        </div>
    </div>

    <div class="text-center mt-5"><a  href="<?= $vehiculos; ?>" class="btn btn-success misV">Ver mis vehículos</a></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/footer.php'; ?>

</body>
</html>