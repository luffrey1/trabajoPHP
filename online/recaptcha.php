<?php
session_start(); // Iniciar la sesión
// 
// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener la respuesta del reCAPTCHA
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Validar la respuesta del reCAPTCHA con Google (sin necesidad de clave secreta)
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldq6LAqAAAAADFOB56yOeeuO3b_kGMhvJvD7Z5n&response=$recaptcha_response");
    $responseKeys = json_decode($response, true);

    // Si la validación es exitosa, redirigir al usuario
    if (intval($responseKeys["success"]) === 1) {
        $_SESSION['recaptcha_verified'] = true;  // Establecer sesión como verificado
        header('Location: /trabajoPHP/index.php');  // Redirigir a la página principal
        exit();
    } else {
        $error_message = "Por favor, completa el reCAPTCHA correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación reCAPTCHA</title>
    <!-- Cargar el script de reCAPTCHA Enterprise -->
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
</head>
<body>

    <div class="container mt-4">
        <h3>Por favor, verifica que no eres un robot</h3>
        
        <!-- Mostrar mensaje de error si la verificación falla -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Formulario de reCAPTCHA -->
        <form action="/trabajoPHP/online/recaptcha.php" method="POST">
            <div class="g-recaptcha" data-sitekey="6Ldq6LAqAAAAAMxrHK1LWg-fj7UNwezOLL8FX0q7" data-action="LOGIN"></div>
            <br>
            <button type="submit" class="btn btn-primary">Verificar</button>
        </form>
    </div>

</body>
</html>
