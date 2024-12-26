<?php
session_start();
require("./database/funciones.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$usuario = obtenerDatosUsuario($user_id);
$imagen_perfil = obtenerImagenUsuario($user_id);

if (!$usuario) {
    echo "No se han encontrado datos del usuario.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar los cambios
    $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : $usuario['nombre'];
    $apellidos = !empty($_POST["apellidos"]) ? $_POST["apellidos"] : $usuario['apellidos'];
    $direccion = !empty($_POST["direccion"]) ? $_POST["direccion"] : $usuario['direccion'];
    $cp = !empty($_POST["cp"]) ? $_POST["cp"] : $usuario['CP'];
    $tlf = !empty($_POST["tlf"]) ? $_POST["tlf"] : $usuario['tlf'];
    $email = !empty($_POST["email"]) ? $_POST["email"] : $usuario['email'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Procesar la nueva foto
        $resultado_imagen = procesarImagenParaBD('foto');
        if (isset($resultado_imagen['error'])) {
            $error_message = $resultado_imagen['error'];
        } else {
            $foto_datos = $resultado_imagen['datos'];
        }
    } else {
        $foto_datos = null;
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
<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand" href="#">MotoCoches</a>
    <button
        class="navbar-toggler d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapsibleNavId"
        aria-controls="collapsibleNavId"
        aria-expanded="false"
        aria-label="Toggle navigation"
    ></button>
    
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <!-- Menú principal de navegación -->
        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link " href="index.php" aria-current="page">
                    Home <span class="visually-hidden">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="perfil.php" aria-current="page">
                    Perfil 
                </a>
            </li>
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="dropdownId"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    ¿Vender?
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownId">
                    <a class="dropdown-item" href="formCoche.php">Coches</a>
                    <a class="dropdown-item" href="formMoto.php">Motos</a>
                </div>
            </li>
        </ul>
        
        <!-- Botones de sesión y registro -->
        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <!-- Botón Iniciar sesión -->
            <li class="nav-item">
                <div class="d-grid gap-3">
                    <a href="login.php">
                        <button type="button" class="btn btn-danger">
                            Iniciar sesión
                        </button>
                    </a>
                </div>
            </li>
            <!-- Botón Registro -->
            <li class="nav-item">
                <div class="d-grid gap-3 ms-4"> <!-- ms-4 agrega margen izquierdo entre los botones -->
                    <a href="signUp.php">
                        <button type="button" class="btn btn-danger">
                            Registro
                        </button>
                    </a>
                </div>
            </li>
        </ul>

        <!-- Formulario de búsqueda -->
        <form class="d-flex my-2 my-lg-0 ms-lg-4">
            <input
                class="form-control me-sm-2"
                type="text"
                placeholder="Search"
            />
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                Search
            </button>
        </form>
    </div>
</nav>
    <div class="container rounded bg-white mt-5 mb-5">
         <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <div class="col-md-3 border-right">
    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
        <?php if ($imagen_perfil): ?>
            <img class="profile-image rounded-circle" width="150px" src="data:image/jpeg;base64,<?= base64_encode($imagen_perfil) ?>" alt="Foto de perfil">
        <?php else: ?>
            <img class="profile-image rounded-circle" width="150px" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
        <?php endif; ?>
        
    </div>
</div>


                </div>
            </div>
            <div class="col-md-9">
                <div class="p-3 py-5">
                    <h4>Ajustes de Perfil</h4>
                    <form action="ajustes.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario->getNombre()?>">
                            
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $usuario->getApellidos()?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= $usuario->getDireccion()?>">
                        </div>
                        <div class="form-group">
                            <label for="cp">Código Postal:</label>
                            <input type="text" class="form-control" id="cp" name="cp" value="<?= $usuario->getCp()?>">
                        </div>
                        <div class="form-group">
                            <label for="tlf">Teléfono:</label>
                            <input type="text" class="form-control" id="tlf" name="tlf" value="<?= $usuario->getTlf()?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $usuario->getEmail()?>">
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