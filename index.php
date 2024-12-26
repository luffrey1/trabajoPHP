<?php
session_start(); 
include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

crearTabla();


// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "No ha iniciado sesión.";
    // Si no está autenticado, redirigir o mostrar el mensaje correspondiente
} else {
    // Si el usuario está autenticado, acceder a la sesión
    $user_id = $_SESSION['user_id'];
    echo "Bienvenido, usuario con ID: " . $user_id;
}
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$vehiculos_por_pagina = 9;  // Tres por fila para que se vean tres cards por fila

// Mostrar los vehículos




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
                <a class="nav-link active" href="index.php" aria-current="page">
                    Home <span class="visually-hidden">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="perfil.php" aria-current="page">
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
<div class="container mt-4">
    <div class="row">
        <div class="col-12 text-center">
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
</div>
 <div class="container mt-4">;
<div class="row">;
<?php  mostrarVehiculos($pagina, $vehiculos_por_pagina);?>

</div>;
</div>;


    
</body>
</html>