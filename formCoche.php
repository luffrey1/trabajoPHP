<?php

session_start();

include("./model/Vehiculo.php");
include("./model/Coche.php");
require("./database/funciones.php");

// Crear tabla de vehículo si no existe
crearTablaVehiculo();

// Inicializar variables y errores
$matricula = $color = $combustible = $precio = $nPuertas = $caballos = $carroceria = $airbags = "";
$matriculaErr = $colorErr = $combustibleErr = $precioErr = $nPuertasErr = $caballosErr = $carroceriaErr = $airbagsErr = "";
$errores = false;

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Función para validar campos


// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $vendedor = obtenerDatosUsuario($user_id);

    if (!$vendedor) {
        echo "Error: No se pudo obtener la información del vendedor. Por favor, inicie sesión nuevamente.";
        exit();
    }

    // Asignar los valores del formulario a las variables
    $matricula = $_POST['matricula'] ?? '';
    $color = $_POST['color'] ?? '';
    $combustible = $_POST['combustible'] ?? '';
    $precio = (float) ($_POST['precio'] ?? 0);  // Convertir precio a float
    $nPuertas = (int) ($_POST['nPuertas'] ?? 0);  // Convertir número de puertas a int
    $caballos = (int) ($_POST['caballos'] ?? 0);  // Convertir caballos a int
    $carroceria = $_POST['carroceria'] ?? '';
    $airbags = (int) ($_POST['airbags'] ?? 0);  // Convertir airbags a int

    // Validar los campos 

    // Procesar la imagen
 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_datos = file_get_contents($_FILES['foto']['tmp_name']);
        if ($foto_datos === false) {
            die("Error al leer los datos del archivo.");
        }
        echo "Tamaño de los datos de la imagen: " . strlen($foto_datos) . " bytes.";
    } else {
        die("Error al subir la imagen: " . $_FILES['foto']['error']);
    }
    
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
        die("Error al subir la imagen: " . $_FILES['foto']['error']);
    }
    

    // Crear el objeto Coche
    $vehiculo = new Coche(
        $matricula,
        $color,
        $combustible,
        $precio,
        $vendedor, 
        $nPuertas,
        $caballos,
        $carroceria,
        $airbags,
        $foto_datos 
    );

    // Insertar el coche en la base de datos
    if (insertarCoche($vehiculo)) {
        echo "Vehículo registrado con éxito.";
    } else {
        echo "Error al registrar el vehículo.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>FormularioCoche</title>
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
                <a class="nav-link " href="perfil.php" aria-current="page">
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
                <div class="dropdown-menu " aria-labelledby="dropdownId">
                    <a class="dropdown-item active" href="formCoche.php">Coches</a>
                    <a class="dropdown-item " href="formMoto.php">Motos</a>
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

<div class="container d-flex justify-content-center align-items-center vh-100">
    <form action="./formCoche.php" method="POST" enctype="multipart/form-data" class="p-4 bg-light rounded shadow">
        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula: *</label>
            <input type="text" class="form-control" name="matricula" value="<?php echo $matricula; ?>">
            <small class="form-text text-muted">
                <?php if (!empty($idErr)) { echo "<div class='text-danger'>$idErr</div>"; } ?>
            </small>
            <span class="errores"><small><?php echo $matriculaErr; ?></small></span>
        </div>

        <div class="mb-3">
            <label for="color" class="form-label">Color: *</label>
            <input type="text" name="color" class="form-control" value="<?php echo $color; ?>">
            <span class="errores"><?php echo $colorErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="combustible" class="form-label">Combustible: *</label>
            <select name="combustible" class="form-control">
                <option value="gasolina" <?php if($combustible=="gasolina") echo "selected"; ?>>Gasolina</option>
                <option value="diesel" <?php if($combustible=="diesel") echo "selected"; ?>>Diesel</option>
                <option value="gasNatural" <?php if($combustible=="gasNatural") echo "selected"; ?>>Gas Natural</option>
                <option value="electricidad" <?php if($combustible=="electricidad") echo "selected"; ?>>Electricidad</option>
            </select>
            <span class="errores"><?php echo $combustibleErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio: *</label>
            <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $precio; ?>">
            <span class="errores"><?php echo $precioErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="nPuertas" class="form-label">Número de Puertas: *</label>
            <input type="number" name="nPuertas" class="form-control" value="<?php echo $nPuertas; ?>">
            <span class="errores"><?php echo $nPuertasErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="caballos" class="form-label">Caballos: *</label>
            <input type="number" name="caballos" class="form-control" value="<?php echo $caballos; ?>">
        </div>

        <div class="mb-3">
            <label for="carroceria" class="form-label">Carrocería: *</label>
            <input type="text" name="carroceria" class="form-control" value="<?php echo $carroceria; ?>">
        </div>

        <div class="mb-3">
            <label for="airbags" class="form-label">Airbags: *</label>
            <input type="number" name="airbags" class="form-control" value="<?php echo $airbags; ?>">
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto del vehículo: *</label>
            <input type="file" id="foto" name="foto" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Añadir Vehículo</button>
    </form>
</div>


<?php include('footer.php'); ?>

</body>
</html>