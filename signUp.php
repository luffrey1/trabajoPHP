<?php

session_start();

include("./model/Vehiculo.php");
include("./model/Coche.php");
include("./model/Moto.php");
require("./database/funciones.php");

crearTabla();
crearTablaVehiculo();

$id = $contra ="";
$idErr = $contraErr = $contrasErr= $contra1Err ="";
$errores = false;
$contra = "";
$contra1 ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Se hace la validacion de datos
    if(!empty($_POST["id"])) {

     $id = $_POST["id"];
    } else {
     $idErr = "El nombre es obligatorio";
     $errores = true;
    }

    if(!empty($_POST["contra"])) {

     $contra = $_POST["contra"];
   
    } else if(empty($_POST["pass"])) {
     $contraErr = "Tienes que introducir la contraseña";
     $errores = true;   
    } 
    if(!empty($_POST["contra1"])) {

      $contra1 = $_POST["contra1"];
    
     } else if(empty($_POST["pass"])) {
      $contra1Err = "Tienes que introducir de nuevo la contraseña";
      $errores = true;   
     } 
     if(!empty($_POST["contra"] == $_POST["contra1"])) {

      $contra = $_POST["contra"];
    
     } else if(empty($_POST["pass"])) {
      $contrasErr = "
               <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <strong>¡Error!</strong> Las contraseñas no coinciden
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
      $errores = true;   
     } 
 
       if (!$errores) {
         // Podriamos poner en futuro que se envie un correo de verificacion, como en las paginas normales
         if (verificarId($id) == true ) {
            insertarUsuario($id, $contra);
             echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
                    <strong>¡Éxito!</strong> $id Se ha registrado correctamente! <a class='nav-item nav-link' href='login.php'>Acceda al INICIO DE SESION: </a>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
         echo "
         <div class='alert alert-danger alert-dismissible fade show' role='alert'>
              <strong>¡Error!</strong> Este usuario ya está en uso.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
         
   
     
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <title>signUp</title>
</head>
<body>
<div class="container">
<?= $contrasErr ?? ''; ?>
<form method="POST" action="./signUp.php"> 
      <div class="mb-3 row">
         <label
            for="id"
            class="col-4 col-form-label"
            >Nombre de usuario: *</label
         >
         <div
            class="col-8"
         >
            <input
               type="text"
               class="form-control"
               name="id"
          
               value="<?php echo $id; ?>"
               
            />
            <small id="emailHelpId" class="form-text text-muted"
                > <?php if (!empty($idErr)) {
                 echo "<div class='text-danger'>$idErr</div>"; 
                 } 
                 ?></small>
         </div>
         <div class="mb-3 row">
         <label
            for="id"
            class="col-4 col-form-label"
            >Contraseña: *</label
         >
         <div
            class="col-8"
         >
            <input
               type="password"
               class="form-control"
               name="contra"
            />
            <small class="form-text text-muted"
                > <?php if (!empty($contraErr)) {
                 echo "<div class='text-danger'>$contraErr</div>"; 
                 } 
                 ?></small>
         </div>
         <div class="mb-3 row">
         <label
            for="id"
            class="col-4 col-form-label"
            >Repita la contraseña: *</label
         >
         <div
            class="col-8"
         >
            <input
               type="password"
               class="form-control"
               name="contra1"
               
            />
            <small id="emailHelpId" class="form-text text-muted"
                > <?php if (!empty($contra1Err)) {
                 echo "<div class='text-danger'>$contra1Err</div>"; 
                 } 
                 ?></small>
         </div>
         <div class="mb-12 row">
         <div
            class="col-12"
         >
         <button
            type="submit"
            class="btn btn-primary"
         >
            Submit
         </button>
         
               </div>

               </div>
      </div>
     
   </form>
</div>






    


</body>
</html>
