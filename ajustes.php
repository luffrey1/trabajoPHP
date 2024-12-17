<?php
session_start();
require("./database/funciones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (!empty($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
    }
    
    if (!empty($_POST["apellidos"])) {
        $apellidos = $_POST["apellidos"];
    }
    if (!empty($_POST["direccion"])) {
        $direccion = $_POST["direccion"];
    }
    if (!empty($_POST["cp"])) {
        $cp = $_POST["cp"];
    }

    if (!empty($_POST["tlf"])) {
        $tlf = $_POST["tlf"];
    }
    if (!empty($_POST["email"])) {
        $email = $_POST["email"];
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
        // Llamar a la función para actualizar los datos
        if (updateUsuario($user_id, $nombre, $apellidos, $direccion, $cp, $tlf, $email)) {
            $success_message = "Los datos se han actualizado correctamente.";
            // Recargar datos actualizados
            $user_data = [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'direccion' => $direccion,
                'CP' => $cp,
                'tlf' => $tlf,
                'email' => $email
            ];
        } else {
            $error_message = "Error al actualizar los datos. Inténtalo de nuevo.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <title>Ajustes de Perfil</title>
</head>
<body>
   
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
            </div>
        </div>
        <div class="col-md-9">
            <div class="p-3 py-5">
                <h4 class="text-right">Ajustes de Perfil</h4>
                <form method="POST" action="ajustes.php"> 
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($user_data['nombre'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" value="<?= htmlspecialchars($user_data['apellidos'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Teléfono</label>
                            <input type="text" class="form-control" name="tlf" value="<?= htmlspecialchars($user_data['tlf'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Dirección</label>
                            <input type="text" class="form-control" name="direccion" value="<?= htmlspecialchars($user_data['direccion'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Código Postal</label>
                            <input type="text" class="form-control" name="cp" value="<?= htmlspecialchars($user_data['CP'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Correo Electrónico</label>
                            <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
