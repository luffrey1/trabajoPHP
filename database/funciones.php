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
        direccion varchar(100) ,
        CP varchar(20),
        cVendidos int,
        tlf varchar(25),
        email varchar(50),
        nombre varchar(50),
        apellidos varchar(50)
        )";
         $conexion->query($sql);
}
function crearTablaC() {
    $conexion = conectar();
    $sql ="CREATE table if not exists Coche (
        matricula varchar(50) primary key,
        color varchar(25) not null,
        combustible varchar(100) not null,
        precio varchar(20) not null,
        vendedor varchar(100) not null,
        comprador varchar(100) not null,
        Puertas int not null,
        CV int not null,
        carroceria varchar(100) not null,
        airbag int not null
        )";
         $conexion->query($sql);
}
function crearTablaM() {
    $conexion = conectar();
    $sql ="CREATE table if not exists Moto (
        matricula varchar(50) primary key,
        color varchar(25) not null,
        combustible varchar(100) not null,
        precio varchar(20) not null,
        vendedor varchar(100) not null,
        comprador varchar(100) not null,
        CC int not null,
        tipo varchar(100) not null,
        baul float
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
    $sql = "SELECT contra FROM Usuario WHERE id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $id);
    $prepared->execute();
    $resultado = $prepared->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $hash = $fila["contra"];
        if (password_verify($contra, $hash)) {
            return true; 
        } else {
            return false; 
        }
    } else {
        return false; 
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
function updateUsuario($id, $nombre, $apellidos, $direccion, $cp, $tlf, $email) {
    $conexion = conectarBD();

    $sql_update = "UPDATE Usuario SET 
                    nombre = ?, 
                    apellidos = ?, 
                    direccion = ?, 
                    CP = ?, 
                    tlf = ?, 
                    email = ? 
                   WHERE id = ?";
    $resultado = $conexion->prepare($sql_update);
    $resultado->bind_param("sssssss", $nombre, $apellidos, $direccion, $cp, $tlf, $email, $id);

    if ($stmt->$resultado()) {
        return true; 
    } else {
        return false;
    }
}


?>