<?php

session_start();
include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

$matricula = $color = $combustible = $precio =
$nPuertas = $caballos = $carroceria = $airbags= $vendedor = "";

$matriculaErr = $colorErr = $combustibleErr = $precioErr =
$nPuertasErr = $caballosErr = $carroceriaErr = $airbagsErr = $vendedorErr = "";

$errores = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["matricula"])) {

        $matricula = $_POST["matricula"];
    } else{
        $matriculaErr="Debes introducir una matrícula";
        $errores=true;
    }

    if(!empty($_POST["color"])){

        $color = $_POST["color"];

    } else{

        $colorErr="Debes introducir el color del vehículo";
        $errores=true;
    }

    if(!empty($_POST["combustible"])){

        $combustible = $_POST["combustible"];

    } else{

        $combustibleErr="Debes introducir el tipo de combustible";
        $errores=true;
    }

    if (!empty($_POST["precio"])) {

        $precio = $_POST["precio"];
        
    } else{
        $precioErr="Debes introducir el precio";
        $errores=true;
    }

    if (!empty($_POST["nPuertas"])) {

        $nPuertas = $_POST["nPuertas"];
        
    } else{
        $nPuertasErr="Debes introducir la cantidad de puertas";
        $errores=true;
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

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">


        <label>Matrícula: *</label>
        <input type="text" name="matricula" value="<?php echo $matricula; ?>"><br>
        <span class="errores"><?php echo $matriculaErr; ?></span><br>

        <label>Color: *</label>
        <input type="text" name="color"><br>
        <span class="errores"><?php echo $colorErr; ?></span><br>

        <label>Combustible: *</label>
        <select class="form-select" id="pais">
            <option name="combustible" value="gasolina" <?php if($combustible=="gasolina") echo "checked"?>>Gasolina</option>
            <option name="combustible" value="diesel" <?php if($combustible=="diesel") echo "checked"?>>Diesel</option>
            <option name="combustible" value="gasNatural" <?php if($combustible=="gasNatural") echo "checked"?>>Gas Natural</option>
            <option name="combustible" value="electricidad" <?php if($combustible=="electricidad") echo "checked"?>>Electricidad</option>
            <option name="combustible" value="etanol" <?php if($combustible=="etanol") echo "checked"?>>Etanol</option>

        </select>
        <span class="errores"><?php echo $combustibleErr; ?></span><br>

        <label>Precio: *</label>
        <input type="number" name="precio"><br>
        <span class="errores"><?php echo $precioErr; ?></span><br>

        <label>Precio: *</label>
        <input type="number" name="nPuertas"><br>
        <span class="errores"><?php echo $nPuertasErr; ?></span><br>


    </form>
    
</body>
</html>