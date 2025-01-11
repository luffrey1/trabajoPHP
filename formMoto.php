<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("./model/Vehiculo.php");
include("./model/Moto.php");
require("./database/funciones.php");

// Crear tabla de vehículo si no existe
crearTablaVehiculo();

// Inicializar variables y errores
$matricula = $color = $combustible = $precio = $cilindrada = $tipo_moto = $baul ="";
$matriculaErr = $colorErr = $combustibleErr = $precioErr = $cilindradaErr = $tipo_motoErr = $baulErr = $fotoErr= "";
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
    $matricula = securizar($_POST['matricula'] ?? '');
    if(empty($matricula)){
        $matriculaErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }
    
    $color = securizar($_POST['color'] ?? '');
    if(empty($color)){
        $colorErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }
  
    $combustible = securizar($_POST['combustible']);

    $precio = (float)securizar($_POST['precio']);

    if(empty($precio)){
        $precioErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }

    $cilindrada = (int)securizar($_POST['cilindrada']);

    if ($cilindrada <= 0) {
        $cilindradaErr = "Es obligatorio rellenar este campo.";
        $errores = true;
    }
    
    $tipo_moto = securizar($_POST['tipo_moto'] ?? '');

    if(empty($tipo_moto)){
        $tipo_motoErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }

    $baul = isset($_POST['baul']) ? 1 : 0;

 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_datos = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        $fotoErr = "Es obligatorio subir una imagen.";
        $errores = true;
        $foto_datos = ""; // Asignar un valor vacío para evitar errores
    }
    

    if ($errores) {
        echo "<div class='alert alert-danger'>No enviado.</div>";
    } else {

        // Crear el objeto MOTO
        $vehiculo = new Moto(
            $matricula,
            $color,
            $combustible,
            $precio,
            $vendedor,
            $cilindrada,
            $tipo_moto,
            isset($_POST['baul']) ? 1 : 0,
            $foto_datos
        );

        // Insertar el coche en la base de datos
        if (insertarMoto($vehiculo)) {
            echo "<div class='alert alert-success'>Vehículo registrado con éxito.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al registrar el vehículo.</div>";
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>Motos</title>
</head>
<style>
    body {
    display: flex;
    flex-direction: column;
    }

    .container {
    flex: 1;
    display: flex; /* El contenedor ocupa el espacio restante */
    }

    .vehiculo {
    max-width: 500px;
    }

    .errores {
        color: red;
    }
</style>
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
                    <a class="dropdown-item " href="formCoche.php">Coches</a>
                    <a class="dropdown-item active" href="formMoto.php">Motos</a>
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
    <form action="./formMoto.php" method="POST" enctype="multipart/form-data" class="vahiculo p-4 bg-light rounded shadow">
        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula: *</label>
            <input type="text" class="form-control" maxlength="7" size="7" name="matricula" value="<?php echo $matricula; ?>">
            <small class="form-text text-muted">
                <?php if (!empty($idErr)) { echo "<div class='text-danger'>$idErr</div>"; } ?>
            </small>
            <span class="errores"><?php echo $matriculaErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio: *</label>
            <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $precio; ?>">
            <span class="errores"><?php echo $precioErr; ?></span>
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
            <label for="color" class="form-label">Color: *</label>
            <input type="text" name="color" class="form-control" value="<?php echo $color; ?>">
            <span class="errores"><?php echo $colorErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="cilindrada" class="form-label">Cilindrada: *</label>
            <input type="number" name="cilindrada" class="form-control" value="<?php echo $cilindrada; ?>">
            <span class="errores"><?php echo $cilindradaErr; ?></span>
        </div>

        <div class="mb-3">
            <label for="tipo_moto" class="form-label">Tipo de moto: *</label>
            <input type="text" name="tipo_moto" class="form-control" value="<?php echo $tipo_moto; ?>">
        </div>

        <div class="mb-3 form-check">
        <input type="checkbox" name="baul" class="form-check-input" <?php if($baul == 1) echo 'checked'; ?>>
            <label for="baul" class="form-check-label">Baul: *</label>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto del vehículo: *</label>
            <input type="file" id="foto" name="foto" class="form-control">
            <span class="errores"><?php echo $fotoErr; ?></span>
        </div>

        <button type="submit" class="btn btn-primary w-100">Añadir Vehículo</button>
    </form>
</div>

<?php include('footer.php'); ?>


    
</body>
</html>