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
       $notificacionError=  "<div class='alert alert-danger'>No enviado.</div>";
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
   <style>
       body {
           display: flex;
           flex-direction: column;
           height: 100vh;
           margin: 0;
       }
       .container {
            margin-top:100px;
           flex: 1;
           display: flex;
           justify-content: center;
           align-items: center;
       }
       .vehiculo {
           max-width: 600px;
           width: 100%;
           padding: 30px;
           background-color: #f8f9fa;
           border-radius: 8px;
           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
       }
       .errores {
           color: red;
       }

   </style>
</head>
<body>

<?php include('./views/header.php'); ?>

<?php if ($errores): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $notificacionError; ?>
    </div>
<?php endif; ?>

<div class="container">
    <form action="./formMoto.php" method="POST" enctype="multipart/form-data" class="vehiculo p-4 bg-light rounded shadow w-100">
        <!-- Primera fila: Matrícula y Precio -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="matricula" class="form-label">Matrícula: *</label>
                <input type="text" class="form-control" maxlength="7" size="7" name="matricula" value="<?php echo $matricula; ?>">
                <small class="form-text text-muted"><?php if (!empty($idErr)) { echo "<div class='text-danger'>$idErr</div>"; } ?></small>
                <span class="errores"><?php echo $matriculaErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="precio" class="form-label">Precio: *</label>
                <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $precio; ?>">
                <span class="errores"><?php echo $precioErr; ?></span>
            </div>
        </div>

        <!-- Segunda fila: Combustible y Color -->
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
                <label for="color" class="form-label">Color: *</label>
                <input type="text" name="color" class="form-control" value="<?php echo $color; ?>">
                <span class="errores"><?php echo $colorErr; ?></span>
            </div>
        </div>

        <!-- Tercera fila: Cilindrada y Tipo de moto -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="cilindrada" class="form-label">Cilindrada: *</label>
                <input type="number" name="cilindrada" class="form-control" value="<?php echo $cilindrada; ?>">
                <span class="errores"><?php echo $cilindradaErr; ?></span>
            </div>
            <div class="col-md-6">
                <label for="tipo_moto" class="form-label">Tipo de moto: *</label>
                <input type="text" name="tipo_moto" class="form-control" value="<?php echo $tipo_moto; ?>">
            </div>
        </div>

        <!-- Cuarta fila: Baúl -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="baul" class="form-check-input" <?php if($baul == 1) echo 'checked'; ?>>
                    <label for="baul" class="form-check-label">Baúl: *</label>
                </div>
            </div>
        </div>

        <!-- Quinta fila: Foto -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="foto" class="form-label">Foto del vehículo: *</label>
                <input type="file" id="foto" name="foto" class="form-control">
                <span class="errores"><?php echo $fotoErr; ?></span>
            </div>
        </div>

        <!-- Botón de Enviar -->
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary w-100">Añadir Vehículo</button>
            </div>
        </div>
    </form>
</div>


<?php include('./views/footer.php'); ?>

</body>
</html>
