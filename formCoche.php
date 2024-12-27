<?php

session_start();

include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");
    crearTablaVehiculo();

$matricula = $color = $combustible = $precio = $nPuertas = $caballos = $carroceria = $airbags = "";
$matriculaErr = $colorErr = $combustibleErr = $precioErr = $nPuertasErr = $caballosErr = $carroceriaErr = $airbagsErr = "";
$errores = false;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $vendedor = obtenerDatosUsuario($user_id);

    // Validar los datos del vehículo antes de procesar el archivo
    if (!$vendedor) {
        echo "Error: No se pudo obtener la información del vendedor. Por favor, asegúrese de haber iniciado sesión correctamente.";
        exit();
    }
    $foto_datos = null;
    if (empty($matricula)) {
        $matriculaErr = "La matrícula es obligatoria.";
        $errores = true;
    }
    if (empty($color)) {
        $colorErr = "El color es obligatorio.";
        $errores = true;
    }
    if (empty($combustible)) {
        $combustibleErr = "El combustible es obligatorio.";
        $errores = true;
    }
    if (empty($precio) || !is_numeric($precio)) {
        $precioErr = "El precio es obligatorio y debe ser un número.";
        $errores = true;
    }
    if (empty($nPuertas) || !is_numeric($nPuertas)) {
        $nPuertasErr = "El número de puertas es obligatorio y debe ser un número.";
        $errores = true;
    }
    if (empty($caballos) || !is_numeric($caballos)) {
        $caballosErr = "Los caballos son obligatorios y deben ser un número.";
        $errores = true;
    }
    if (empty($carroceria)) {
        $carroceriaErr = "La carrocería es obligatoria.";
        $errores = true;
    }
    if (empty($airbags) || !is_numeric($airbags)) {
                $airbagsErr = "Los airbags son obligatorios y deben ser un número.";
            }
        

    // Si no hay errores, proceder con la inserción
    // Si no hay errores, proceder con la inserción
    if (!$errores) {
        // Verificar si se ha subido un archivo
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = $_FILES['foto'];
            $foto_tmp = $foto['tmp_name'];
            $foto_tipo = $foto['type'];
            $foto_tamano = $foto['size'];
            $max_file_size = 2 * 1024 * 1024; // 2MB
            $permitidos = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($foto_tipo, $permitidos)) {
                if ($foto_tamano <= $max_file_size) {
                    // Leer el contenido del archivo
                    $foto_datos = file_get_contents($foto_tmp);
                    if ($foto_datos === false) {
                        echo "Error al leer el archivo.";
                        exit();
                    }

                    // Crear el objeto Coche
                    $vehiculo = new Coche(
                        $matricula, 
                        $color, 
                        $combustible, 
                        $precio, 
                        $vendedor, // Pasamos el objeto Usuario aquí
                        $nPuertas, 
                        $caballos, 
                        $carroceria, 
                        $airbags,
                        $foto_datos // Pasamos los datos binarios de la imagen
                    );

                    // Insertar el coche en la base de datos
                    insertarCoche($vehiculo);
                } else {
                    echo "El archivo es demasiado grande. El tamaño máximo permitido es 2MB.";
                }
            } else {
                echo "Tipo de archivo no permitido.";
            }
        } else {
            echo "Error al subir la foto.";
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
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>FormularioCoche</title>
</head>
<body>

<form action="./perfil.php" method="POST">

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

    <label>Numero de Puertas: *</label>
    <input type="number" name="nPuertas" value="<?php echo $nPuertas; ?>"><br>
    <span class="errores"><?php echo $nPuertasErr; ?></span><br>

    <label>Caballos: *</label>
    <input type="number" name="caballos" value="<?php echo $caballos; ?>"><br>

    <label>Carrocería: *</label>
    <input type="text" name="carroceria" value="<?php echo $carroceria; ?>"><br>

    <label>Airbags: *</label>
    <input type="number" name="airbags" value="<?php echo $airbags; ?>"><br>

    <label>Foto del vehiculo: *</label>
    <input type="file" id="foto" name="foto"><br>

 

    <input type="submit" value="Añadir Vehículo">
</form>


    
</body>
</html>