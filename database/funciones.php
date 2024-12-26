<?php


function conectar() {
    $server = "127.0.0.1"; // localhost
    $user = "root";
    $pass = "Sandia4you";
    $dbname = "daw";
    return new mysqli($server, $user, $pass, $dbname);
}
function crearTabla() {
    $conexion = conectar();
    // estan sin not null porque eso luego se lo pedimos al usuario que 
    // lo actualicé antes de podeer poner a vender o comprar un coche.
    // me refiero a direccion, cp cvendidos y tlf
    $sql ="CREATE table if not exists Usuario (
        id varchar(50) primary key not null,
        contra varchar(255) not null,
        nombre varchar(100) not null,
        direccion varchar(100) ,
        CP varchar(20) ,
        tlf varchar(25)
        )";
         $conexion->query($sql);
}

function crearTablaVehiculo(){
    $conexion = conectar();

    $sql = "CREATE TABLE IF NOT EXISTS vehiculo (
    matricula varchar(50) primary key,
    tipo enum ('c', 'm') not null,
    color varchar(25) not null,
    combustible varchar(100) not null,
    precio decimal(5,2) not null,
    cv int default null, --ambos
    n_puertas int default null, --coches
    carroceria varchar(50) default null, --coches
    airbag int default null, --coches
    cc int default null, --motos
    tipo_moto varchar(50) default null, --motos
    baul bit default null, --motos
    )";

    $conexion->query($sql);
}


function insertarUsuario($id, $pass) {
    $conexion = conectar();
    $sql = "INSERT Into Usuario (id, contra) VALUES (?, ?)";
    $prepared = $conexion->prepare($sql);
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $prepared->bind_param("ss", $id, $pass);
    $prepared->execute();
}
function verificarUsuario($id, $contra) {
    $conexion = conectar();
    $sql = "SELECT contra from Usuario where id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $id);
    $prepared->execute();
    $resultado = $prepared->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $hash = $fila["contra"];
        if(password_verify($contra, $hash)) {
            echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
            <strong>¡Éxito!</strong> Iniciaste Sesion!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        } else {
             echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>¡Error!</strong> Contraseña incorrecta! 
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        <strong>¡Error!</strong> Este usuario no existe
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
} 
function verificarId($id):bool {
    $conexion = conectar();
    $sql = "SELECT * from Usuario where id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $id);
    $prepared->execute();
    $resultado = $prepared->get_result();
    if ($resultado->num_rows > 0) { 
        return false;


    } else {
        return true;
    }
}


?>