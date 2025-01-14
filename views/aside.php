<?php


$profilePath = '/trabajoPHP/perfil/perfil.php';
$ajustesPath = '/trabajoPHP/perfil/ajustes.php';
$vehiculosPath = '/trabajoPHP/perfil/vehiculos.php';
if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy(); 

    if (isset($_COOKIE['user_id'])) {
        setcookie('user_id', '', time() - 3600, "/"); 
    }

    header("Location: /trabajoPHP/inicio/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$usuario = obtenerDatosUsuario($user_id);
$imagen_perfil = obtenerImagenUsuario($user_id) ?? '';
?>

<aside>
  <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="height: 95vh; width: 250px; position: fixed; margin-top: 26px;">
    <!-- Logo y nombre del Sidebar -->
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
   
    </a>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Menú de navegación -->
    <ul class="nav flex-column mb-auto">
    
      <li>
        <a href="#" class="nav-link text-white">
          <i class="bi bi-grid-3x3-gap me-2"></i> Dashboard
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <i class="bi bi-cart4 me-2"></i> Orders
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <i class="bi bi-box me-2"></i> Novedades
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <i class="bi bi-person-circle me-2"></i> Contactos
        </a>
      </li>
    </ul>

    <hr class="text-white">

    <!-- Usuario y dropdown -->
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
      <?php if ($imagen_perfil): ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen_perfil); ?>" alt="" width="32" height="32" class="rounded-circle me-2">
        <?php else: ?>
            <img src="https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp" alt="" width="32" height="32" class="rounded-circle me-2">
        <?php endif; ?>
        <strong><?= $usuario->getId()?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="<?= $vehiculosPath; ?>">Ver mis vehiculos...</a></li>
        <li><a class="dropdown-item" href="<?= $ajustesPath; ?>">Ajustes</a></li>
        <li><a class="dropdown-item"  href="<?= $profilePath; ?>">Perfil</a></li>
        <li><hr class="dropdown-divider"></li>
        
        <!-- Formulario de Cerrar sesión -->
        <li>
            <form method="POST" class="d-inline">
                <button type="submit" name="logout" class="dropdown-item btn btn-link text-decoration-none">Cerrar sesión</button>
            </form> 
        </li>
      </ul>
    </div>
  </div>
</aside>

<!-- Contenido principal -->


