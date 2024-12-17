<?php
function conectar() {
    $server = "127.0.0.1"; // localhost
    $user = "root";
    $pass = "1234"; // Sandia4you en clase
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
        apellidos varchar(50),
        foto LONGBLOB
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
function updateUsuario($user_id, $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_url = null) {
    $conexion = conectar();
    if ($foto_url !== null) { 
        $sql = "UPDATE usuario
        SET nombre = ?, apellidos = ?, direccion = ?, CP = ?, tlf = ?, email = ?, foto = ? 
        WHERE id = ?";
         if ($prepared = $conexion->prepare($sql)) {
       
        $prepared->bind_param("ssssssss", $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_url, $user_id);
         }
    }  else {
        $sql = "UPDATE usuario
        SET nombre = ?, apellidos = ?, direccion = ?, CP = ?, tlf = ?, email = ?
        WHERE id = ?";
         if ($prepared = $conexion->prepare($sql)) {
       
        $prepared->bind_param("sssssss", $nombre, $apellidos, $direccion, $cp, $tlf, $email, $user_id);
    }
}
        if ($prepared->execute()) {
            $prepared->close();
            $conexion->close();
            return true;
        } else {
            error_log("Error al ejecutar la consulta: " . $prepared->error);
        }

    $conexion->close();
    return false; // Si algo falló
    }

function procesarImagenParaBD($input_name) {
    if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES[$input_name];

        // Validar el tipo de archivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($foto["type"], $tipos_permitidos)) {
            return ['error' => "Formato de archivo no permitido. Solo se permiten JPEG, PNG o GIF."];
        }

        // Leer el contenido del archivo como binario
        $datos_imagen = file_get_contents($foto["tmp_name"]);
        if ($datos_imagen === false) {
            return ['error' => "Error al procesar la imagen."];
        }

        // Devolver los datos binarios de la imagen y su tipo MIME
        return ['datos' => $datos_imagen, 'tipo' => $foto["type"]];
    }

    return ['error' => "No se ha seleccionado ninguna imagen o hubo un error en la subida."];
}
function obtenerImagenUsuario($user_id) {
    $conexion = conectar();
    
    $sql = "SELECT foto FROM Usuario WHERE id = ?";
    
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $user_id);
    $prepared->execute();
    
    $result = $prepared->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Mensaje de depuración
       
        return $row['foto'];  // Esto devuelve la imagen en formato LONGBLOB
    }
    
    return null;  // Si no se encuentra la imagen
}
function obtenerDatosUsuario($user_id) {
    $conexion = conectar();
    $sql = "SELECT id, nombre, apellidos, email, tlf, direccion, cp FROM Usuario WHERE id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("i", $user_id);  // 'i' para un tipo entero
    $prepared->execute();
    $result = $prepared->get_result();

    return $result->fetch_assoc();  // Devuelve un array asociativo con los datos del usuario
}







?>