<?php

session_start();

// Incluir la biblioteca de Stripe 
require_once('vendor/autoload.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Venta.php';

// Tu clave secreta de Stripe
\Stripe\Stripe::setApiKey('sk_test_51QfhMpFhEizoamwmJ1MvAyB1ChTNVbxyzoSfuGeRbIn1X2W2bjFjM75gecEnDWZ0PzHmJoay01V6z7TBScQkG1r200DmG6LFkE');
// Obtener los datos del formulario
$precio = $_POST['precio'];
$tipo = $_POST['tipo'];
$matricula = $_POST['matricula'];
$stripeToken = $_POST['stripeToken']; 
$vehiculo = obtenerDatosVehiculo($matricula,$tipo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2>Confirmación de Compra</h2>
            </div>
            <div class="card-body">
                <?php
                try {
                    // Crear el cargo usando la API de Stripe
                    $charge = \Stripe\Charge::create([
                        'amount' => $precio, 
                        'currency' => 'eur', 
                        'description' => 'Compra del vehículo',
                        'source' => $stripeToken,
                    ]);

                    // Aqui el pago se realiza si te funcionan las extensiones que hay que activar en PHP.INI
                    echo "<div class='alert alert-success'>";
                    echo "<h4>¡Compra exitosa!</h4>";
                    echo "<p>Has comprado el vehículo con matrícula: <strong>" . $matricula . "</strong></p>";
echo "<p>Total pagado: <strong>" . number_format($precio / 100, 2) . "€</strong></p>";
                    echo "</div>";
                    $id1 = obtenerVendedor($matricula);
                    $vendedor = obtenerDatosUsuario($id1);
                    $id = $_SESSION['user_id'];
                    $comprador = obtenerDatosUsuario($id);
                    $fecha= time();
                    $fechaDateTime = new DateTime();
                    $fechaDateTime->setTimestamp($fecha);
                    $venta = new Venta (
                        $vehiculo,
                        $comprador,
                        $vendedor,
                        $fechaDateTime
                    );
                    registrarVenta($venta);
                    comprarVehiculo($matricula);


                } catch (\Stripe\Exception\CardException $e) {
                    // Si ocurre un error con el pago 
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>Error en la compra</h4>";
                    echo "<p>Lo sentimos, hubo un problema al procesar tu pago. Intenta de nuevo más tarde.</p>";
                    echo "</div>";
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    // Si ocurre un error con la API de Stripe
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>Error en la compra</h4>";
                    echo "<p>Hubo un problema al contactar con el servicio de pago. Intenta de nuevo más tarde.</p>";
                    echo "</div>";
                }
                ?>

                <!-- Botón para volver a la página principal -->
                <div class="text-center">
                    <a href="/trabajoPHP/index.php" class="btn btn-primary">Volver a la página principal</a>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>