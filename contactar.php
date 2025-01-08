<?php
session_start();
require("./database/funciones.php");
// Verificamos si la matrícula está pasada en la URL
if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    // Guardamos los detalles del vehículo en la sesión
    $vehiculo = obtenerDatosVehiculo($matricula);
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
$imagen_perfil = obtenerImagenUsuario($vehiculo['vendedor']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vehículo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vehicle-info {
            margin-top: 20px;
            padding: 30px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .vehicle-info img {
            width: 100%;
            height: auto;
            max-width: 400px; /* Establece un tamaño fijo para todas las imágenes */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-left: 14vh;
        }

        .buy-button {
            background-color: #28a745;
            color: white;
            padding: 15px 40px;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .buy-button:hover {
            background-color: #218838;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }

        .vehicle-detail {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .container {
            max-width: 800px;
            margin-top: 40px;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .imagen {
            margin-right:13vh;
        }
        .vendedor {
            
        }
    
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand" href="#">MotoCoches</a>
    <button
        class="navbar-toggler d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapsibleNavId"
        aria-controls="collapsibleNavId"
        aria-expanded="false"
        aria-label="Toggle navigation"
    ></button>
    
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <!-- Menú principal de navegación -->
        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link " href="index.php" aria-current="page">
                    Home <span class="visually-hidden">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="perfil.php" aria-current="page">
                    Perfil 
                </a>
            </li>
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="dropdownId"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    ¿Vender?
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownId">
                    <a class="dropdown-item" href="formCoche.php">Coches</a>
                    <a class="dropdown-item" href="formMoto.php">Motos</a>
                </div>
            </li>
        </ul>
        
        <!-- Botones de sesión y registro -->
        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <!-- Botón Iniciar sesión -->
            <li class="nav-item">
                <div class="d-grid gap-3">
                    <a href="login.php">
                        <button type="button" class="btn btn-danger">
                            Iniciar sesión
                        </button>
                    </a>
                </div>
            </li>
            <!-- Botón Registro -->
            <li class="nav-item">
                <div class="d-grid gap-3 ms-4"> <!-- ms-4 agrega margen izquierdo entre los botones -->
                    <a href="signUp.php">
                        <button type="button" class="btn btn-danger">
                            Registro
                        </button>
                    </a>
                </div>
            </li>
        </ul>

        <!-- Formulario de búsqueda -->
        <form class="d-flex my-2 my-lg-0 ms-lg-4">
            <input
                class="form-control me-sm-2"
                type="text"
                placeholder="Search"
            />
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                Search
            </button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="vehicle-info">
        <h2 class="text-center mb-4">Detalles del Vehículo</h2>

        <?php if (isset($vehiculo)): ?>
            <div class="card mb-4">
              
                <div class="row card-body">
                    <div class="col col-6">
                    <p class="vehicle-detail"><strong>Matrícula:</strong> <?php echo $vehiculo['matricula']; ?></p>
                    <p class="vehicle-detail"><strong>Color:</strong> <?php echo $vehiculo['color']; ?></p>
                    <p class="vehicle-detail"><strong>Combustible:</strong> <?php echo $vehiculo['combustible']; ?></p>
                    <p class="vehicle-detail"><strong>Precio:</strong> <?php echo $vehiculo['precio']; ?>€</p>
                    </div>
                    <div class="col col-6">
                    <p class="vehicle-detail"><strong>Caballos:</strong> <?php echo $vehiculo['cv']; ?></p>
                    <p class="vehicle-detail"><strong>Número de Puertas:</strong> <?php echo $vehiculo['n_puertas']; ?></p>
                    <p class="vehicle-detail"><strong>Carrocería:</strong> <?php echo $vehiculo['carroceria']; ?></p>
                    <p class="vehicle-detail"><strong>Airbags:</strong> <?php echo $vehiculo['airbag']; ?></p>
                   
                    </div>
 
       
                   <hr>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($vehiculo['foto']); ?>" alt="Foto del Vehículo">

                    <div class="mt-4 text-center">
                        <!-- Botón para proceder a la compra -->
                        <a href="sandbox_de_tarjetas.php" class="buy-button">Comprar Coche</a>
                    </div>
                </div>
            </div>
            <div class="card mb-4" style="max-width: 300px; border: 1px solid #ddd; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <div class="row card-body d-flex flex-column text-center">
        <!-- Nombre del vendedor -->
        <p class="vehicle-detail"><strong>Vendedor:</strong> <?php echo $vehiculo['vendedor']; ?></p>

        <!-- Imagen de perfil centrada -->
        <div class="vendedor d-flex flex-column justify-content-center align-items-center">
            <?php if ($imagen_perfil): ?>
                <img class="imagen profile-image rounded-circle w-50" src="data:image/jpeg;base64,<?= base64_encode($imagen_perfil) ?>" alt="Foto de perfil">
            <?php else: ?>
                <img class="imagen profile-image rounded-circle w-50" src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="Imagen predeterminada">
            <?php endif; ?>
        </div>

        <!-- Botón para contactar -->
        <a href="contactar.php" class="btn btn-primary mt-3">Contactar</a>
    </div>
</div>



        <?php else: ?>
            <p>Detalles del vehículo no disponibles.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
