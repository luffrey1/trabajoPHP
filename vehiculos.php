<?php
    session_start();
    require("./database/funciones.php");

    
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $vehiculos_por_pagina = 9;  // Tres por fila para que se vean tres cards por fila

    // Calcular el total de páginas
    $total_paginas = calcularPaginas($vehiculos_por_pagina);


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
        $matricula = $_POST['matricula']; // Obtener la matrícula enviada por el formulario
    
        if (borrarVehiculo($matricula)) {
            echo '<div class="alert alert-success text-center mt-5">El vehículo con matrícula ' . htmlspecialchars($matricula) . ' fue eliminado con éxito.</div>';
        } else {
            echo '<div class="alert alert-danger text-center mt-5">Error al eliminar el vehículo con matrícula ' . htmlspecialchars($matricula) . '.</div>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Mis Vehículos</title>
</head>
<body>
<?php include('./views/header.php'); ?>

        <div class="col-md-9">
            <div class="container mt-3">
                <div class="row">
                    <div class = "col-12 text-center mt-5">
                        <h3>Mis Vehículos</h3>
                    </div>
                    <div class="col-12 text-center mt-5">
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
        </div>

    <?php 
        vehiculosUsuario($_SESSION['user_id'], $pagina, $vehiculos_por_pagina);
    ?>

    <?php include('./views/footer.php'); ?>
</body>
</html>