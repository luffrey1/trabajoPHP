<?php
session_start(); 
require 'vendor/autoload.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Venta.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Vehiculo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Usuario.php';

\Stripe\Stripe::setApiKey('sk_test_51QfhMpFhEizoamwmJ1MvAyB1ChTNVbxyzoSfuGeRbIn1X2W2bjFjM75gecEnDWZ0PzHmJoay01V6z7TBScQkG1r200DmG6LFkE');
if (isset($_GET['matricula'])) {

    $matricula = $_GET['matricula'];
    
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
    
   
    }
    $vehiculo = obtenerDatosVehiculo($matricula,$tipo);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['stripeToken'];
  
    // Verifica que el token haya sido recibido
    if (!$token) {
        die('Error: No se ha recibido el token de Stripe');
    }

    try {
        // Realiza el cargo a la tarjeta usando el token
        $charge = \Stripe\Charge::create([
            'amount' => 1000,  // Monto en centavos (ej. 10.00€)
            'currency' => 'eur',
            'source' => $token,
            'description' => 'Compra de Vehículo',
        ]);

        // Verifica si el pago fue exitoso
        if ($charge->status == 'succeeded') {
            echo "Pago realizado exitosamente. Gracias por su compra.";

           
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
            
        } else {
            echo "El pago no se pudo procesar.";
        }
    } catch (\Stripe\Exception\CardException $e) {
        // Captura errores de Stripe
        echo 'Error: ' . $e->getMessage();
    }
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Vehículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Pagar Vehículo</h1>
    <p class="text-center">Vehículo con matrícula: <strong><?php echo $vehiculo->getMatricula(); ?></strong></p>
    <p class="text-center">El precio del vehículo es: <strong><?php echo number_format($vehiculo->getPrecio() / 100, 2); ?> €</strong></p>
    
    <form id="payment-form" method="POST" action="/trabajoPHP/tarjeta/confirmacion.php">
        <div id="card-element" class="form-control my-4"></div>
        <div id="card-errors" role="alert" class="text-danger"></div>
        
        <input type="hidden" name="precio" value="<?php echo $vehiculo->getPrecio(); ?>"> <!-- Precio en centavos -->
        <input type="hidden" name="matricula" value="<?php echo $vehiculo->getMatricula(); ?>"> <!-- ID del vehículo -->
        
        <button class="btn btn-success w-100" type="submit">Proceder al Pago</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51QfhMpFhEizoamwmxovNYyIv902Go0fxzV3z6GtTmVmyn4UznJzbsJFfS9quht5iAZFlJwTDCjGNEyenXbD9L5E000ujzClQsz'); // Reemplaza con tu clave publicable
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>