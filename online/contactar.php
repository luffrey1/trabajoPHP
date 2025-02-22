<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';


// Verificamos si la matrícula está pasada en la URL
if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    // Guardamos los detalles del vehículo en la sesión
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
        
        $vehiculo = obtenerDatosVehiculo($matricula,$tipo);
        $id = $vehiculo->getVendedor();
        $imagen = obtenerImagenUsuario($id);
        $nombre = obtenerNombreUsuario($id);
        
     

    }
    if ($vehiculo) {
        $_SESSION['vehiculo'] = $vehiculo;
    } else {
        echo "Vehículo no encontrado.";
        exit();
    }
} else {
    echo "No se ha recibido matrícula.";
    exit();
}


 // $imagen_perfil = obtenerImagenUsuario($vehiculo['vendedor']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Detalles del Vehículo</title>
    <link rel="stylesheet" href=../views/contactar.css>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/header.php'; ?>

<div class="container">
    <div class="vehicle-info">
        <h2 class="text-center mb-4">Detalles del Vehículo</h2>

        <?php if (isset($vehiculo)): ?>
            <div class="card mb-4">
              
                <div class="row card-body">
                    <div class="col col-6">
                    <p class="vehicle-detail"><strong>Matrícula:</strong> <?php echo $vehiculo->getMatricula(); ?></p>
                    <p class="vehicle-detail"><strong>Color:</strong> <?php echo $vehiculo->getColor(); ?></p>
                    <p class="vehicle-detail"><strong>Combustible:</strong> <?php echo $vehiculo->getCombustible(); ?></p>
                    <p class="vehicle-detail"><strong>Precio:</strong> <?php echo $vehiculo->getPrecio(); ?>€</p>
                    </div>
                    <div class="col col-6">
<?php
                if ($tipo == "c") {
                echo "<p class='vehicle-detail'><strong>Caballos:</strong> " . $vehiculo->getCaballos() . "</p>";
                } else {
                echo "<p class='vehicle-detail'><strong>Cilindrada:</strong> " . $vehiculo->getCilindrada() . "</p>";
                }
?>

<?php
                if ($tipo == "c") {
                echo "<p class='vehicle-detail'><strong>Puertas:</strong> " . $vehiculo->getPuertas() . "</p>";
                } else {
                echo "<p class='vehicle-detail'><strong>Tipo:</strong> " . $vehiculo->getTipo_m() . "</p>";
                }
?>
<?php
                if ($tipo == "c") {
                echo "<p class='vehicle-detail'><strong>Carroceria:</strong> " . $vehiculo->getCarroceria() . "</p>";
                } else {
                echo "<p class='vehicle-detail'><strong>Baul:</strong> " . $vehiculo->getBaul() . "</p>";
                }
?>
<?php
                if ($tipo == "c") {
                echo "<p class='vehicle-detail'><strong>Airbag:</strong> " . $vehiculo->getAirbag() . "</p>";
                } else {
               
                }
?>
        
                   
                    </div>
 
       
                   <hr>
                   
                   <?php
if ($tipo == "c") {
    // Mostrar imagen para coche
    echo '<img src="data:image/jpeg;base64,' . base64_encode($vehiculo->getImagen()) . '" alt="Foto del Vehículo">';
} else {
    // Mostrar imagen para moto
    echo '<img src="data:image/jpeg;base64,' . base64_encode($vehiculo->getImagen()) . '" alt="Foto de la Moto">';
}
?>

<div class="mt-4 text-center">
    <!-- Botón para proceder a la compra -->
    <a href="/trabajoPHP/tarjeta/pago.php?matricula=<?php echo urlencode($vehiculo->getMatricula()); ?>&tipo=<?php echo urlencode($tipo); ?>" class="buy-button">Comprar coche</a>

  
    
</div>

                </div>
            </div>
            <div class="card mb-4" style="max-width: 300px; border: 1px solid #ddd; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <div class="row card-body d-flex flex-column text-center">
        <!-- Nombre del vendedor -->
        <p class="vehicle-detail"><strong>Vendedor:</strong> <?php echo $nombre ?></p>

        <!-- Imagen de perfil centrada -->
        <div class="vendedor d-flex flex-column justify-content-center align-items-center">
            <?php if ($imagen): ?>
                <img class="imagen profile-image rounded-circle w-50" src="data:image/jpeg;base64,<?= base64_encode($imagen) ?>" alt="Foto de perfil"  style="width:200px; height:100px;">
            <?php else: ?>
                <img class="imagen profile-image rounded-circle w-50" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
            <?php endif; ?>
        </div>

        <!-- Botón para contactar -->
        <a href="/trabajoPHP/online/perfilPublico.php?vendedor_id=<?= $id ?>" class="btn btn-primary mt-3">Contactar</a>

      

    </div>
</div>



        <?php else: ?>
            <p>Detalles del vehículo no disponibles.</p>
        <?php endif; ?>
        
    </div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/views/footer.php'; ?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
