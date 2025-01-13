<?php
session_start();

include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

crearTabla();
crearTablaVehiculo();

$id = $contra = $contra1 = "";
$idErr = $contraErr = $contra1Err = $contrasErr = "";
$errores = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de datos
    if (!empty($_POST["id"])) {
        $id = $_POST["id"];
    } else {
        $idErr = "El ID es obligatorio";
        $errores = true;
    }

    if (!empty($_POST["contra"])) {
        $contra = $_POST["contra"];
    } else {
        $contraErr = "Tienes que introducir la contraseña";
        $errores = true;
    }

    if (!empty($_POST["contra1"])) {
        $contra1 = $_POST["contra1"];
    } else {
        $contra1Err = "Tienes que introducir de nuevo la contraseña";
        $errores = true;
    }

    // Validar si las contraseñas coinciden
    if ($contra !== $contra1) {
        $contrasErr = "
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>¡Error!</strong> Las contraseñas no coinciden.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        $errores = true;
    }

    // Si no hay errores, proceder con la inserción
    if (!$errores) {
        $direccion = !empty($_POST["direccion"]) ? $_POST["direccion"] : NULL;
        $cp = !empty($_POST["cp"]) ? $_POST["cp"] : NULL;
        $cVendidos = !empty($_POST["cVendidos"]) ? $_POST["cVendidos"] : 0; // si pones 0 da error
        $tlf = !empty($_POST["tlf"]) ? $_POST["tlf"] : NULL;
        $email = !empty($_POST["email"]) ? $_POST["email"] : NULL;
        $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : NULL;
        $apellidos = !empty($_POST["apellidos"]) ? $_POST["apellidos"] : NULL;
        $imagen = !empty($_FILES["imagen"]["name"]) ? $_FILES["imagen"]["name"] : NULL;

        // Crear el objeto usuario
        $usuario = new Usuario($id, $contra, $direccion, $cp, $cVendidos, $tlf, $email, $nombre, $apellidos, $imagen);
        
        if (verificarId($id)) {
            insertarUsuario($usuario);
            Header("Location: login.php");
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>¡Error!</strong> Este usuario ya está en uso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 113vh;
            background: linear-gradient(to top, #c9c9ff 50%, #9090fa 90%) no-repeat;
        }

        .container {
            margin: 50px auto;
          
        }

        .btn.btn-primary {
            margin-top: 20px;
            border-radius: 15px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-75">
    <div class="card border bg-white p-4" style="width: 28rem;">
        <h3 class="text-center pt-3 font-weight-bold">Registro</h3>
        <form action="signUp.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id" class="form-label">Nombre de usuario:</label>
                <input type="text" class="form-control" name="id" id="id" value="<?= ($id); ?>">
                <small class="form-text text-danger"><?= $idErr; ?></small>
            </div>

            <div class="mb-3">
                <label for="contra" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="contra" id="contra">
                <small class="form-text text-danger"><?= $contraErr; ?></small>
            </div>

            <div class="mb-3">
                <label for="contra1" class="form-label">Repite la contraseña:</label>
                <input type="password" class="form-control" name="contra1" id="contra1">
                <small class="form-text text-danger"><?= $contra1Err; ?></small>
            </div>
            <div class="text-center pt-4 text-muted">
                    ¿Ya tienes una cuenta? <a href="./login.php" class="text-primary">Login</a>
                </div>
            <?= $contrasErr; ?>

            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
    </div>
</div>
</body>
</html>
