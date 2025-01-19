<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Vehiculo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Coche.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Moto.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';
// Crear tabla de vehículo si no existe
crearTablaVehiculo();

// Inicializar variables y errores
$matricula = $color = $combustible = $precio = $nPuertas = $caballos = $carroceria = $airbags = "";
$matriculaErr = $colorErr = $combustibleErr = $precioErr = $nPuertasErr = $caballosErr = $carroceriaErr = $airbagsErr = $fotoErr= "";
$errores = false;
$notificacionEx='';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: /trabajoPHP/inicio/login.php");
    exit();
}

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

    $nPuertas = (int)securizar($_POST['nPuertas']);

    if ($nPuertas <= 0) {
        $nPuertasErr = "Es obligatorio rellenar este campo.";
        $errores = true;
    }

    $caballos = (int)securizar($_POST['caballos']);

    if ($caballos <= 0) {
        $caballosErr = "Es obligatorio rellenar este campo con un número.";
        $errores = true;
    }

    $carroceria = $_POST['carroceria'] ?? '';

    if(empty($carroceria)){
        $carroceriaErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }

    $airbags = (int)securizar($_POST['airbags']);
    
    if(empty($airbags)){
        $airbagsErr = "Es obligatorio rellenar este campo.";
        $errores=true;
    }


    // Procesar la imagen
 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_datos = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        $fotoErr = "Es obligatorio subir una imagen.";
        $errores = true;
        $foto_datos = ""; 
    }

    
    if ($errores) {
        $notificacionErr = "<div class='alert alert-danger alerta'>No enviado.</div>";
    }else{
        $notificacionEx = "<div class='alert alert-success alerta2'>Subido.</div>";
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
   <link rel="stylesheet" href=../views/formCoche.css>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>FormularioCoche</title>
</head>
<style>

   </style>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/header.php'; ?>

<?php
    if ($errores) {
        echo $notificacionErr;
    } else if ($errores!=true) {
        echo $notificacionEx;
    }
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <form action="/trabajoPHP/forms/formCoche.php" method="POST" enctype="multipart/form-data" class="vehiculo p-4 bg-light rounded shadow w-100">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="matricula" class="form-label">Matrícula: *</label>
                <input type="text" class="form-control" maxlength="7" size="7" name="matricula" value="<?php echo $matricula; ?>">
                <span class="errores"><?php echo $matriculaErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="color" class="form-label">Color: *</label>
                <input type="text" name="color" class="form-control" value="<?php echo $color; ?>">
                <span class="errores"><?php echo $colorErr; ?></span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="combustible" class="form-label">Combustible: *</label>
                <select name="combustible" class="form-control">
                    <option value="gasolina" <?php if($combustible=="gasolina") echo "selected"; ?>>Gasolina</option>
                    <option value="diesel" <?php if($combustible=="diesel") echo "selected"; ?>>Diesel</option>
                    <option value="gasNatural" <?php if($combustible=="gasNatural") echo "selected"; ?>>Gas Natural</option>
                    <option value="electricidad" <?php if($combustible=="electricidad") echo "selected"; ?>>Electricidad</option>
                </select>
                <span class="errores"><?php echo $combustibleErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="precio" class="form-label">Precio: *</label>
                <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $precio; ?>">
                <span class="errores"><?php echo $precioErr; ?></span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nPuertas" class="form-label">Número de Puertas: *</label>
                <input type="number" name="nPuertas" class="form-control" value="<?php echo $nPuertas; ?>">
                <span class="errores"><?php echo $nPuertasErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="caballos" class="form-label">Caballos: *</label>
                <input type="number" name="caballos" class="form-control" value="<?php echo $caballos; ?>">
                <span class="errores"><?php echo $caballosErr; ?></span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="carroceria" class="form-label">Carrocería: *</label>
                <input type="text" name="carroceria" class="form-control" value="<?php echo $carroceria; ?>">
                <span class="errores"><?php echo $carroceriaErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="airbags" class="form-label">Airbags: *</label>
                <input type="number" name="airbags" class="form-control" value="<?php echo $airbags; ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="foto" class="form-label">Foto del vehículo: *</label>
                <input type="file" id="foto" name="foto" class="form-control">
                <span class="errores"><?php echo $fotoErr; ?></span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Añadir Vehículo</button>
    </form>
</div>



<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/footer.php'; ?>

</body>
</html>