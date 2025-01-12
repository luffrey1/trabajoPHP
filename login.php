<?php
session_start(); 
include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

crearTabla();

$id = $contra = "";
$idErr = $contraErr = "";
$errores = false;
//Si hay sesion activa redirige al index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Esto es para iniciar sesion con la cookie
if (isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id']; 
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["id"])) {
        $id = $_POST["id"];
    } else {
        $idErr = "El nombre es obligatorio";
        $errores = true;
    }

    if (!empty($_POST["contra"])) {
        $contra = $_POST["contra"];
    } else {
        $contraErr = "Tienes que introducir la contraseña";
        $errores = true;
    }

    if (!$errores) {
        if (verificarUsuario($id, $contra)) {
            $usuario = new Usuario($id, $contra);
            $_SESSION['user_id'] = $id; 
    
            
            if (isset($_POST['mantenerSesion'])) {
              
                setcookie('user_id', $id, time() + (30 * 24 * 60 * 60), "/"); //  30 dias
            }
    
            header("Location: index.php"); // Redirigir a la página principal
            exit();
        } else {
            $contraErr = "Usuario o contraseña incorrectos";
        }
    }
    
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <title>Login</title>
</head>
<body>
<div class="container">
   <form method="POST" action="login.php"> 
      <div class="mb-3 row">
         <label for="id" class="col-4 col-form-label">Nombre de usuario: *</label>
         <div class="col-8">
            <input type="text" class="form-control" name="id" value="<?= htmlspecialchars($id); ?>" />
            <small class="form-text text-danger">
                <?= !empty($idErr) ? $idErr : ''; ?>
            </small>
         </div>
      </div>
      <div class="mb-3 row">
         <label for="contra" class="col-4 col-form-label">Contraseña: *</label>
         <div class="col-8">
            <input type="password" class="form-control" name="contra" />
            <small class="form-text text-danger">
                <?= !empty($contraErr) ? $contraErr : ''; ?>
            </small>
         </div>
      </div>
      <div class="mb-12 row">
         <div class="col-12">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <div class="btn-group" role="group" data-bs-toggle="buttons">
                <label
                    class="btn btn-success active"
                >
                    <input
                        type="checkbox"
                        class="me-2"
                        name="mantenerSesion"
                        id="mantenerSesion"
                        checked
                        autocomplete="off"
                    />
                    ¿Desea mantener la sesion iniciada?
                </label>
               
            </div>
            
         </div>
      </div>
   </form>
</div>
</body>
</html>
