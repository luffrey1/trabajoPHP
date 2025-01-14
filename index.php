<?php
session_start(); 
include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

crearTabla();
crearTablaVehiculo();

if (!isset($_SESSION['recaptcha_verified']) || $_SESSION['recaptcha_verified'] !== true) {
    // Si no está verificado, redirigir a recaptcha.php
    header('Location: /trabajoPHP/online/recaptcha.php');
    exit();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "No ha iniciado sesión.";
    // Si no está autenticado, redirigir o mostrar el mensaje correspondiente
} else {
    // Si el usuario está autenticado, acceder a la sesión
    $user_id = $_SESSION['user_id'];

}
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$vehiculos_por_pagina = 9;  // Tres por fila para que se vean tres cards por fila

// Calcular el total de páginas
$total_paginas = calcularPaginas($vehiculos_por_pagina);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>Bootstrap5</title>
   <style>
      
   </style>
</head>

<body>
<?php include('./views/header.php'); ?>
<?php
if (!isset($_SESSION['user_id'])) {
    echo "No ha iniciado sesión.";
    // Si no está autenticado, redirigir o mostrar el mensaje correspondiente
} else {
    include('./views/aside.php'); 

}
?>

<div class="container-fluid mt-4">
    
    <div class="row">
        <!-- Sidebar -->

        <?php
            if (!isset($_SESSION['user_id'])) {
                
                // Si no está autenticado, redirigir o mostrar el mensaje correspondiente
            } else {
                echo '<div class="col-md-3 sidebar">';
  
                echo '</div>';

            }
            ?>
        <div class="col-md-9">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mt-5">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagina > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?>">Anterior</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagina < $total_paginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?>">Siguiente</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
<div class="container mt-4">
    <h3>Filtros de Búsqueda</h3>
    <form id="filterForm" method="GET" action="index.php"> <!-- Aquí cambias index.php a pagina_vehiculos.php -->
        <div class="row">
            <!-- Filtro de Tipo de Vehículo -->
            <div class="col-md-3 mb-3">
                <label for="tipo">Tipo de Vehículo</label>
                <select id="tipo" name="tipo" class="form-control">
                    <option value="">Todos</option>
                    <option value="c" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'c' ? 'selected' : '' ?>>Coche</option>
                    <option value="m" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'm' ? 'selected' : '' ?>>Moto</option>
                </select>
            </div>

            <!-- Filtro de Rango de Precio -->
            <div class="col-md-3 mb-3">
                <label for="precio">Precio (hasta)</label>
                <input type="range" id="precio" name="precio" min="0" max="50000" step="1000" class="form-control"
                    value="<?= isset($_GET['precio']) ? $_GET['precio'] : 50000 ?>">
                <small class="form-text text-muted">Modificar el rango de precios</small>
            </div>

            <!-- Filtro de Color -->
            <div class="col-md-3 mb-3">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" class="form-control" 
                    value="<?= isset($_GET['color']) ? $_GET['color'] : '' ?>">
            </div>

            <!-- Filtro de Caballos -->
            <div class="col-md-3 mb-3">
                <label for="cv">Caballos</label>
                <input type="number" id="cv" name="cv" class="form-control" placeholder="Ej. 100" 
                    value="<?= isset($_GET['cv']) ? $_GET['cv'] : '' ?>">
            </div>
        </div>

        <div class="row">
            <!-- Filtro de Carrocería -->
            <div class="col-md-3 mb-3">
                <label for="carroceria">Carrocería:</label>
                <input type="text" id="carroceria" name="carroceria" class="form-control" 
                    value="<?= isset($_GET['carroceria']) ? $_GET['carroceria'] : '' ?>">
            </div>

            <!-- Filtro de Combustible -->
            <div class="col-md-3 mb-3">
                <label for="combustible">Combustible:</label>
                <select id="combustible" name="combustible" class="form-control">
                    <option value="">Todos</option>
                    <option value="gasolina" <?= isset($_GET['combustible']) && $_GET['combustible'] == 'gasolina' ? 'selected' : '' ?>>Gasolina</option>
                    <option value="diesel" <?= isset($_GET['combustible']) && $_GET['combustible'] == 'diesel' ? 'selected' : '' ?>>Diesel</option>
                    <option value="electricidad" <?= isset($_GET['combustible']) && $_GET['combustible'] == 'electricidad' ? 'selected' : '' ?>>Eléctrico</option>
                    <option value="hibrido" <?= isset($_GET['combustible']) && $_GET['combustible'] == 'hibrido' ? 'selected' : '' ?>>Híbrido</option>
                </select>
            </div>

            <!-- Filtro de Número de Puertas -->
            <div class="col-md-3 mb-3">
                <label for="n_puertas">Número de Puertas</label>
                <input type="number" id="n_puertas" name="n_puertas" class="form-control" 
                    value="<?= isset($_GET['n_puertas']) ? $_GET['n_puertas'] : '' ?>">
            </div>

            <!-- Filtro de Airbags -->
            <div class="col-md-3 mb-3">
                <label for="airbag">Airbags</label>
                <input type="number" id="airbag" name="airbag" class="form-control" 
                    value="<?= isset($_GET['airbag']) ? $_GET['airbag'] : '' ?>">
            </div>
        </div>

        <div class="row">
            <!-- Filtro de Cilindrada (cc) -->
            <div class="col-md-3 mb-3">
                <label for="cc">Cilindrada (cc)</label>
                <input type="number" id="cc" name="cc" class="form-control" 
                    value="<?= isset($_GET['cc']) ? $_GET['cc'] : '' ?>">
            </div>

            <!-- Filtro de Tipo de Moto -->
            <div class="col-md-3 mb-3">
                <label for="tipo_moto">Tipo de Moto:</label>
                <select id="tipo_moto" name="tipo_moto" class="form-control">
                    <option value="">Todos</option>
                    <option value="scooter" <?= isset($_GET['tipo_moto']) && $_GET['tipo_moto'] == 'scooter' ? 'selected' : '' ?>>Scooter</option>
                    <option value="deportiva" <?= isset($_GET['tipo_moto']) && $_GET['tipo_moto'] == 'deportiva' ? 'selected' : '' ?>>Deportiva</option>
                    <option value="chopper" <?= isset($_GET['tipo_moto']) && $_GET['tipo_moto'] == 'chopper' ? 'selected' : '' ?>>Chopper</option>
                    <option value="touring" <?= isset($_GET['tipo_moto']) && $_GET['tipo_moto'] == 'touring' ? 'selected' : '' ?>>Touring</option>
                </select>
            </div>

            <!-- Filtro de Baúl (Sí o No) -->
            <div class="col-md-3 mb-3">
                <label for="baul">Baúl</label>
                <select id="baul" name="baul" class="form-control">
                    <option value="">Todos</option>
                    <option value="1" <?= isset($_GET['baul']) && $_GET['baul'] == '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= isset($_GET['baul']) && $_GET['baul'] == '0' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Filtrar</button>
        <button type="reset" class="btn btn-secondary">Limpiar</button>
    </form>
</div>

<?php 
    mostrarVehiculos($pagina, $vehiculos_por_pagina);
?>
<?php include('./views/footer.php'); ?>


    
</body>
</html>