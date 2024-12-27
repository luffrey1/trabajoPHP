<?php

session_start();

include("./model/Vehiculo.php");
include("./model/Moto.php");
require("./database/funciones.php");

// Crear tabla de vehículo si no existe
crearTablaVehiculo();

// Inicializar variables y errores
$matricula = $color = $combustible = $precio = $cilindrada = $tipo_moto = $baul ="";
$matriculaErr = $colorErr = $combustibleErr = $precioErr = $cilindradaErr = $tipo_motoErr = $baulErr = "";
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
    $precio = $_POST['precio'] ?? '';  
    $cilindrada = ($_POST['cilindrada'] ?? '');  // Convertir número de puertas a int
    $tipo_moto = $_POST['tipo_moto'] ?? '';  
    $baul = $_POST['baul'] ?? '';

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
    $vehiculo = new Moto(
        $matricula,
        $color,
        $combustible,
        $precio,
        $vendedor, 
        $cilindrada,
        $tipo_moto,
        $baul,
        
    );

    // Insertar el coche en la base de datos
    if (insertarMoto($vehiculo)) {
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>FormularioCoche</title>
</head>
<body>

<form action="./formCoche.php" method="POST" enctype="multipart/form-data">

<div class="mb-3 row"></div>
    <label for="id" class="col-4 col-form-label">
        Matrícula: *
    </label>

    <div class="col-8">
        <input type="text" name="matricula" value="<?php echo $matricula; ?>">
        <small id="emailHelpId" class="form-text text-muted">
            <?php
            if (!empty($idErr)) {
                echo "<div class='text-danger'>$idErr</div>"; 
            } 
            ?>
        </small>
    </div>

    <span class="errores"><?php echo $matriculaErr; ?></span><br>

    <label>Color: *</label>
    <input type="text" name="color" value="<?php echo $color; ?>"><br>
    <span class="errores"><?php echo $colorErr; ?></span><br>

    <label>Combustible: *</label>
    <select name="combustible">
        <option value="gasolina" <?php if($combustible=="gasolina") echo "selected"; ?>>Gasolina</option>
        <option value="diesel" <?php if($combustible=="diesel") echo "selected"; ?>>Diesel</option>
        <option value="gasNatural" <?php if($combustible=="gasNatural") echo "selected"; ?>>Gas Natural</option>
        <option value="electricidad" <?php if($combustible=="electricidad") echo "selected"; ?>>Electricidad</option>
    </select><br>
    <span class="errores"><?php echo $combustibleErr; ?></span><br>

    <label>Precio: *</label>
    <input type="number" name="precio" value="<?php echo $precio; ?>"><br>
    <span class="errores"><?php echo $precioErr; ?></span><br>

    <label>Cilindrada: *</label>
    <input type="number" name="cilindrada" value="<?php echo $cilindrada; ?>"><br>

    <label>Tipo de moto: *</label>
    <input type="text" name="tipo_moto" value="<?php echo $tipo_moto; ?>"><br>

    <label>:Baul: *</label>
    <input type="checkbox" name="baul" value="<?php echo $baul; ?>"><br>

    <label>Foto del vehiculo: *</label>
    <input type="file" id="foto" name="foto"><br>

 
    <input type="submit" value="Añadir Vehículo">
</form>


    
</body>
</html>