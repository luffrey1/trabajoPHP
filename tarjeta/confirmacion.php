<?php
// Asegúrate de que la sesión esté iniciada para acceder a la información del vehículo
session_start();

// Incluir la biblioteca de Stripe (si no lo has hecho ya)
require_once('vendor/autoload.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';


// Tu clave secreta de Stripe
\Stripe\Stripe::setApiKey('sk_test_51QfhMpFhEizoamwmJ1MvAyB1ChTNVbxyzoSfuGeRbIn1X2W2bjFjM75gecEnDWZ0PzHmJoay01V6z7TBScQkG1r200DmG6LFkE'); // Reemplaza con tu clave secreta de Stripe

// Obtener los datos del formulario
$precio = $_POST['precio'];
$matricula = $_POST['matricula'];
$stripeToken = $_POST['stripeToken']; // Este es el token enviado desde el formulario

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
                        'amount' => $precio, // El precio debe estar en centavos (ej. 1000 para 10.00)
                        'currency' => 'eur', // Puedes ajustar la moneda a la que estés usando
                        'description' => 'Compra del vehículo',
                        'source' => $stripeToken,
                    ]);

                    // Si el pago fue exitoso
                    echo "<div class='alert alert-success'>";
                    echo "<h4>¡Compra exitosa!</h4>";
                    echo "<p>Has comprado el vehículo con matrícula: <strong>" . $matricula . "</strong></p>";
echo "<p>Total pagado: <strong>" . number_format($precio / 100, 2) . "€</strong></p>";
                    echo "</div>";
                    comprarVehiculo($matricula);

                } catch (\Stripe\Exception\CardException $e) {
                    // Si ocurre un error con el pago (por ejemplo, tarjeta rechazada)
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

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>