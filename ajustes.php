    <?php
    session_start();
    require("./database/funciones.php");
    $nombre = $apellidos = $direccion = $cp = $tlf = $email = $foto_datos = $foto_tipo = null;
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        if (!empty($_POST["nombre"])) {
            $nombre = $_POST["nombre"];
        }
        $user_id = $_SESSION['user_id'];

        
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
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Procesar la nueva foto
            $resultado_imagen = procesarImagenParaBD('foto');
            if (isset($resultado_imagen['error'])) {
                $error_message = $resultado_imagen['error'];
            } else {
                $foto_datos = $resultado_imagen['datos'];
                $foto_tipo = $resultado_imagen['tipo'];
            }
        } else {
            // Si no se subió una nueva foto, no cambiar la foto actual
            $foto_datos = null;
            $foto_tipo = null;
        }
        if (isset($_SESSION['user_id'])) {
            if (updateUsuario($user_id, $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_datos ?? null, $foto_tipo ?? null)) {
                $success_message = "Los datos se han actualizado correctamente.";
            } else {
                $error_message = "Error al actualizar los datos.";
            }
        
        } else {
            header("Location: login.php");
            exit();
            // Llamar a la función para actualizar los datos
        }
    
    }
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
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
                <a class="nav-link active" href="index.php" aria-current="page">
                    Home <span class="visually-hidden">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="perfil.php" aria-current="page">
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
                <?php if (isset($_SESSION['user_id'])): ?>
                <img class="rounded-circle mt-5" width="150px" src="./database/ver_imagen.php?id=<?= $_SESSION['user_id'] ?>">
                <?php else: ?>
                    <img class="rounded-circle mt-5" width="150px"  src="./database/1.jpeg">
                <?php endif; ?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="p-3 py-5">
                    <h4 class="text-right">Ajustes de Perfil</h4>
                    <form method="POST" action="ajustes.php" enctype="multipart/form-data"> 
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Nombre</label>
                                <input type="text" class="form-control" name="nombre">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Teléfono</label>
                                <input type="text" class="form-control" name="tlf">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Dirección</label>
                                <input type="text" class="form-control" name="direccion" value=>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Código Postal</label>
                                <input type="text" class="form-control" name="cp" value=>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Correo Electrónico</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Imagen perfil:</label>
                                <input type="file" class="form-control" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
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
