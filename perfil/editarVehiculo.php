<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: /trabajoPHP/inicio/login.php");
    exit();
}


$matricula = $_GET['matricula'] ?? null;

if (!$matricula) {
    echo "Matrícula no proporcionada";
    exit();
}

$tipo = obtenerTipoV($matricula);
$vehiculo = obtenerDatosVehiculo($matricula,$tipo);
$foto = $vehiculo->getImagen();
// $vehiculo = vehiculoPorMatricula($matricula);



if (!$vehiculo) {
    echo "No se encontró el vehículo";
    exit();
}




// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $color = $_POST['color'];
    $combustible = $_POST['combustible'];
    $precio = $_POST['precio'];
    $foto =obtenerImagenVehiculo($matricula);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        
    }
    


    if ($tipo === 'c') { 
        $n_puertas = $_POST['n_puertas'];
        $carroceria = $_POST['carroceria'];
        $cv = $_POST['cv'];
        $airbags = $_POST['airbags'];

        actualizarCoche($matricula, $color, $combustible, $precio, $n_puertas, $carroceria, $cv, $airbags, $foto);
    } elseif ($tipo === 'm') { 
        $cc = $_POST['cc'];
        $tipo_moto = $_POST['tipo_moto'];
        $baul = isset($_POST['baul']) ? 1 : 0;
   
        actualizarMoto($matricula, $color, $combustible, $precio, $cc, $tipo_moto, $baul, $foto);
    }

    $success_message = "<div class='alert alert-success'>El vehículo se actualizó correctamente.</div>";


}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Editar Vehículo</title>
</head>
<body>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/header.php'; ?>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>Editar Vehículo</h2>
                    </div>
                </div>
                <?php if (isset($vehiculo)): ?>
                    <div class="row justify-content-center mb-4">
                        <div class="col-6 text-center mt-3">
                            <?php if ($foto): ?>
                                <img class="img-thumbnail" style="width: 300px; height: 180px;" src="data:image/jpeg;base64,<?= base64_encode($foto) ?>" alt="Imagen del vehículo">
                            <?php else: ?>
                                <img class="img-thumbnail" style="width: 300px; height: 180px;" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
                            <?php endif; ?>
                        </div>
                    </div>
               
                    <form method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="matricula" class="form-label">Matrícula:</label>
                                    <input type="text" class="form-control" id="matricula" name="matricula" value="<?= $vehiculo->getMatricula()?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color:</label>
                                    <input type="text" class="form-control" id="color" name="color" value="<?= $vehiculo->getColor()?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="combustible" class="form-label">Combustible:</label>
                                    <select class="form-select" id="combustible" name="combustible">
                                        <option value="gasolina" <?php $vehiculo->getCombustible() === 'gasolina' ? 'selected' : '' ?>>Gasolina</option>
                                        <option value="diesel"  <?php $vehiculo->getCombustible() === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                                        <option value="gas_natural" <?php $vehiculo->getCombustible() === 'gasolina' ? 'selected' : '' ?>>Gasolina</option>
                                        <option value="electricidad"  <?php $vehiculo->getCombustible() === 'electricidad' ? 'selected' : '' ?>>Electricidad</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio:</label>
                                    <input type="number" class="form-control" id="precio" name="precio" value="<?= $vehiculo->getPrecio() ?>">
                                </div>
                            </div>
                        </div>

                        <?php if ($tipo === 'c'): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="n_puertas" class="form-label">Número de Puertas:</label>
                                        <input type="number" class="form-control" id="n_puertas" name="n_puertas" value="<?= $vehiculo->getPuertas() ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="carroceria" class="form-label">Carrocería:</label>
                                        <input type="text" class="form-control" id="carroceria" name="carroceria" value="<?= $vehiculo->getCarroceria() ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cv" class="form-label">Caballos:</label>
                                        <input type="number" class="form-control" id="cv" name="cv" value="<?= $vehiculo->getCaballos() ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="airbags" class="form-label">Airbags:</label>
                                        <input type="number" class="form-control" id="airbags" name="airbags" value="<?= $vehiculo->getAirbag() ?>">
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($tipo === 'm'): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cc" class="form-label">Cilindrada:</label>
                                        <input type="number" class="form-control" id="cc" name="cc" value="<?= $vehiculo->getCilindrada() ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_moto" class="form-label">Tipo de Moto:</label>
                                        <input type="text" class="form-control" id="tipo_moto" name="tipo_moto" value="<?= $vehiculo->getTipo_m() ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="baul" name="baul" <?= $vehiculo->getBaul()?>>
                                        <label class="form-check-label" for="baul">Baúl</label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Imagen:</label>
                                    <input type="file" class="form-control" id="foto" name="foto" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </div>
                    </form>

                <?php endif; ?>

            </div>
        </div>
    </div>
    
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/footer.php'; ?>
</body>
</html>