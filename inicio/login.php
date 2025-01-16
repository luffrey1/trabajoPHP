<?php
session_start(); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Vehiculo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Coche.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/model/Moto.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trabajoPHP/database/funciones.php';


crearTabla();

$id = $contra = "";
$idErr = $contraErr = "";
$errores = false;
//Si hay sesion activa redirige al index
if (isset($_SESSION['user_id'])) {
    header("Location: /trabajoPHP/index.php");
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
        $id = securizar($_POST["id"]);
    } else {
        $idErr = "El nombre es obligatorio";
        $errores = true;
    }

    if (!empty($_POST["contra"])) {
        $contra = securizar($_POST["contra"]);
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
    
            header("Location: /trabajoPHP/index.php");
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
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif
}

body {
    height: 100vh;
    background: linear-gradient(to top, #c9c9ff 50%, #9090fa 90%) no-repeat
}

.container {
    margin: 50px auto
}

.panel-heading {
    text-align: center;
    margin-bottom: 10px
}

#forgot {
    min-width: 100px;
    margin-left: auto;
    text-decoration: none
}

a:hover {
    text-decoration: none
}

.form-inline label {
    padding-left: 10px;
    margin: 0;
    cursor: pointer
}

.btn.btn-primary {
    margin-top: 20px;
    border-radius: 15px
}

.panel {
    min-height: 380px;
    box-shadow: 20px 20px 80px rgb(218, 218, 218);
    border-radius: 12px
}

.input-field {
    border-radius: 5px;
    padding: 5px;
    display: flex;
    align-items: center;
    cursor: pointer;
    border: 1px solid #ddd;
    color: #4343ff
}

input[type='text'],
input[type='password'] {
    border: none;
    outline: none;
    box-shadow: none;
    width: 100%
}

.fa-eye-slash.btn {
    border: none;
    outline: none;
    box-shadow: none
}

img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    position: relative
}

a[target='_blank'] {
    position: relative;
    transition: all 0.1s ease-in-out
}

.bordert {
    border-top: 1px solid #aaa;
    position: relative
}

.bordert:after {
    content: "or connect with";
    position: absolute;
    top: -13px;
    left: 33%;
    background-color: #fff;
    padding: 0px 8px
}

@media(max-width: 360px) {
    #forgot {
        margin-left: 0;
        padding-top: 10px
    }

    body {
        height: 100%
    }

    .container {
        margin: 30px 0
    }

    .bordert:after {
        left: 25%
    }
}
</style>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-75">
        <div class="card border bg-white p-4" style="width: 28rem;">
            <h3 class="text-center pt-3 font-weight-bold">Login</h3>
            <form action="/trabajoPHP/inicio/login.php" method="POST">
                <div class="mb-3">
                    <label for="id" class="form-label">Nombre de usuario o correo:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-user"></i></span>
                        <input type="text" class="form-control" name="id" id="id"  value="<?= ($id); ?>"placeholder="Username or Email" >
                    </div>
                    <small class="form-text text-danger">
                        <?= !empty($idErr) ? $idErr : ''; ?>
                    </small>
                </div>

                <div class="mb-3">
                    <label for="contra" class="form-label">Contraseña:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="contra" id="contra" placeholder="Enter your Password" >
                    </div>
                    <small class="form-text text-danger">
                        <?= !empty($contraErr) ? $contraErr : ''; ?>
                    </small>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="mantenerSesion" name="mantenerSesion">
                    <label class="form-check-label" for="mantenerSesion">Mantener sesión iniciada</label>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    <a href="#" id="forgot" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="text-center pt-4 text-muted">
                    ¿No tienes una cuenta? <a href="./signUp.php" class="text-primary">Regístrate</a>
                </div>
            </form>

            <hr>

            <div class="text-center py-3">
                <a href="https://www.facebook.com" target="_blank" class="px-2">
                    <img src="https://www.dpreview.com/files/p/articles/4698742202/facebook.jpeg" alt="Facebook" style="width: 30px;">
                </a>
                <a href="https://www.google.com" target="_blank" class="px-2">
                    <img src="https://www.freepnglogos.com/uploads/google-logo-png/google-logo-png-suite-everything-you-need-know-about-google-newest-0.png" alt="Google" style="width: 30px;">
                </a>
                <a href="https://www.github.com" target="_blank" class="px-2">
                    <img src="https://www.freepnglogos.com/uploads/512x512-logo-png/512x512-logo-github-icon-35.png" alt="GitHub" style="width: 30px;">
                </a>
            </div>
        </div>
    </div>
</body>
</html>