<?php
session_start();
require("./database/funciones.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener datos actuales del usuario
$conexion = conectar();
$sql = "SELECT nombre, apellidos, direccion, CP, tlf, email, foto FROM Usuario WHERE id = ?";
$prepared = $conexion->prepare($sql);
$prepared->bind_param("s", $user_id);
$prepared->execute();
$result = $prepared->get_result();
$usuario = $result->fetch_assoc();

$nombre = $usuario['nombre'];
$apellidos = $usuario['apellidos'];
$direccion = $usuario['direccion'];
$cp = $usuario['CP'];
$tlf = $usuario['tlf'];
$email = $usuario['email'];
$foto_datos = $usuario['foto']; // Esto contiene los datos binarios de la foto o null si no hay
$foto_url = $foto_datos ? "./database/ver_imagen.php?id=$user_id" : "./database/default.jpg"; // Ruta para la imagen

$prepared->close();
$conexion->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Validar campos enviados y mantener los existentes si están vacíos
    $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : $nombre;
    $apellidos = !empty($_POST["apellidos"]) ? $_POST["apellidos"] : $apellidos;
    $direccion = !empty($_POST["direccion"]) ? $_POST["direccion"] : $direccion;
    $cp = !empty($_POST["cp"]) ? $_POST["cp"] : $cp;
    $tlf = !empty($_POST["tlf"]) ? $_POST["tlf"] : $tlf;
    $email = !empty($_POST["email"]) ? $_POST["email"] : $email;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Procesar la nueva foto
        $resultado_imagen = procesarImagenParaBD('foto');
        if (isset($resultado_imagen['error'])) {
            $error_message = $resultado_imagen['error'];
        } else {
            $foto_datos = $resultado_imagen['datos'];
        }
    } else {
        $foto_datos = null; // No cambiar la foto si no se sube ninguna
    }

    // Actualizar datos en la base de datos
    if (actualizarUsuario($user_id, $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_datos)) {
        $success_message = "Los datos se han actualizado correctamente.";
    } else {
        $error_message = "Error al actualizar los datos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ajustes de Perfil</title>
</head>
<body>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" width="150px" src="<?= $foto_url ?>">
                </div>
            </div>
            <div class="col-md-9">
                <div class="p-3 py-5">
                    <h4>Ajustes de Perfil</h4>
                    <form action="ajustes.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($direccion) ?>">
                        </div>
                        <div class="form-group">
                            <label for="cp">Código Postal:</label>
                            <input type="text" class="form-control" id="cp" name="cp" value="<?= htmlspecialchars($cp) ?>">
                        </div>
                        <div class="form-group">
                            <label for="tlf">Teléfono:</label>
                            <input type="text" class="form-control" id="tlf" name="tlf" value="<?= htmlspecialchars($tlf) ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto (opcional):</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>
                        <button class="btn btn-primary mt-3" type="submit">Guardar Cambios</button>
                    </form>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success mt-3"><?= $success_message ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger mt-3"><?= $error_message ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
