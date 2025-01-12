<?php
if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy(); 

    if (isset($_COOKIE['user_id'])) {
        setcookie('user_id', '', time() - 3600, "/"); 
    }

    header("Location: login.php");
    exit();
}

$paginaActual = basename($_SERVER['PHP_SELF']);
?>

<header style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; width: 100%; padding: 0;">
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary" style="height: 50px;">
        <a class="navbar-brand" href="#">MotoCoches</a>
        <button
            class="navbar-toggler d-lg-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapsibleNavId"
            aria-controls="collapsibleNavId"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <!-- Menú principal de navegación -->
            <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $paginaActual === 'index.php' ? 'active' : ''; ?>" href="index.php" aria-current="page">
                        Home <span class="visually-hidden"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $paginaActual === 'perfil.php' ? 'active' : ''; ?>" href="perfil.php" aria-current="page">
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
                        <a class="dropdown-item <?php echo $paginaActual === 'formCoche.php' ? 'active' : ''; ?>" href="formCoche.php">Coches</a>
                        <a class="dropdown-item <?php echo $paginaActual === 'formMoto.php' ? 'active' : ''; ?>" href="formMoto.php">Motos</a>
                    </div>
                </li>
            </ul>

            <!-- Botones de sesión y registro -->
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <div class="d-grid gap-2 d-md-block">
                        <a href="login.php">
                            <button type="button" class="btn btn-danger">Iniciar sesión</button>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="d-grid gap-2 d-md-block ms-2">
                        <a href="signUp.php">
                            <button type="button" class="btn btn-danger">Registro</button>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>