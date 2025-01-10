<?php
require 'vendor/autoload.php'; 

// Configuración de Stripe con tu clave secreta
\Stripe\Stripe::setApiKey('sk_test_51QfhMpFhEizoamwmJ1MvAyB1ChTNVbxyzoSfuGeRbIn1X2W2bjFjM75gecEnDWZ0PzHmJoay01V6z7TBScQkG1r200DmG6LFkE');

// Obtener el precio del producto desde contact.php (puedes enviarlo con una solicitud GET o POST)
$precio = isset($_POST['precio']) ? $_POST['precio'] : 100;  // Si no se pasa el precio, usamos 100 como valor predeterminado

// Recibir datos de la tarjeta desde el frontend (JavaScript)
$token = $_POST['stripeToken'];  // El token generado por Stripe JS

// Crear un cargo a la tarjeta
try {
    $charge = \Stripe\Charge::create([
        'amount' => $precio * 100,  // El monto se da en centavos
        'currency' => 'usd',  // Puedes cambiar la moneda si es necesario
        'source' => $token,  // El token generado por Stripe.js
        'description' => 'Pago por producto',
    ]);

    // Si el pago es exitoso, respondemos con un mensaje de éxito
    echo json_encode(['status' => 'success', 'message' => 'Pago realizado con éxito']);
} catch (\Stripe\Exception\CardException $e) {
    // Si hay un error con la tarjeta, lo mostramos
    echo json_encode(['status' => 'error', 'message' => $e->getError()->message]);
}
?>
<!-- contact.php -->
<html>
<head>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Formulario de Pago</h2>
    <form action="api_pago.php" method="POST" id="payment-form">
        <label for="card-element">
            Tarjeta de Crédito
        </label>
        <div id="card-element">
            <!-- Un elemento de tarjeta será insertado aquí -->
        </div>

        <div id="card-errors" role="alert"></div>
        <button type="submit" id="submit-button">Pagar</button>
    </form>

    <script>
        var stripe = Stripe('pk_test_your_publishable_key_here');  // Usa tu clave publicable de Stripe
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        // Crear el elemento de tarjeta
        var card = elements.create("card", { style: style });
        card.mount("#card-element");

        // Manejo del formulario de pago
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Informar al usuario si hay un error
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Enviar el token a tu servidor
                    var form = document.getElementById('payment-form');
                    var tokenInput = document.createElement('input');
                    tokenInput.setAttribute('type', 'hidden');
                    tokenInput.setAttribute('name', 'stripeToken');
                    tokenInput.setAttribute('value', result.token.id);
                    form.appendChild(tokenInput);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>

