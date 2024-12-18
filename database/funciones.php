<?php
require(__DIR__ . '/../model/Usuario.php');

function conectar() {
    $server = "127.0.0.1"; // localhost
    $user = "root";
    $pass = "Sandia4you"; // 1234 en clase
    $dbname = "daw";
    return new mysqli($server, $user, $pass, $dbname);
}
function crearTabla() {
    $conexion = conectar();
    // estan sin not null porque eso luego se lo pedimos al Usuario que 
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
        foto LONGBLOB,
        tipo_foto VARCHAR(50)
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
function insertarUsuario($Usuario) {
    $conexion = conectar();
    $sql = "INSERT Into Usuario (id, contra ,direccion,CP,cVendidos,tlf,email,nombre,apellidos,foto) VALUES (?, ?,?,?,?,?,?,?,?,?)";
    $prepared = $conexion->prepare($sql);
    $pass = password_hash($Usuario->contra, PASSWORD_DEFAULT);
    $prepared->bind_param("ssssisssss",
    $Usuario->id,
    $pass,
    $Usuario->direccion,
    $Usuario->cp,
    $Usuario->cVendidos,
    $Usuario->tlf,
    $Usuario->email,
    $Usuario->nombre,
    $Usuario->apellidos,
    $Usuario->imagen
    );
    $prepared->execute();
}

function verificarUsuario( $id, $contra) {
    $conexion = conectar();
    $sql = "SELECT contra FROM Usuario WHERE id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $id);
    $prepared->execute();
    
    $result = $prepared->get_result();
    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $hash = $fila["contra"];
        return password_verify($contra, $hash);
    }
    return false;
}


// creo que esta funcion de abajo no se utiliza?¿?¿?
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

function actualizarUsuario($user_id, $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_url = null) {
    $conexion = conectar();
    if ($foto_url !== null) { 
        $sql = "UPDATE Usuario
        SET nombre = ?, apellidos = ?, direccion = ?, CP = ?, tlf = ?, email = ?, foto = ? 
        WHERE id = ?";
         if ($prepared = $conexion->prepare($sql)) {
       
        $prepared->bind_param("ssssssss", $nombre, $apellidos, $direccion, $cp, $tlf, $email, $foto_url, $user_id);
         }
    }  else {
        $sql = "UPDATE Usuario
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





function procesarImagenParaBD($campo) {
    // Verificamos que se haya subido un archivo y no haya errores
    if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES[$campo]['tmp_name'];
        $foto_tipo = $_FILES[$campo]['type'];
        
        // Verificamos si el archivo es una imagen válida (en este caso jpg o png)
        if ($foto_tipo === 'image/jpeg' || $foto_tipo === 'image/png') {
            // Leemos el contenido binario de la imagen
            $foto_datos = file_get_contents($foto_tmp);
            return ['datos' => $foto_datos, 'tipo' => $foto_tipo];
        } else {
            return ['error' => 'El archivo no es una imagen válida (debe ser JPG o PNG).'];
        }
    } else {
        return ['error' => 'No se ha subido ninguna imagen o ha ocurrido un error en la carga.'];
    }
}



function obtenerImagenUsuario($user_id) {
    $conexion = conectar(); 
    $sql = "SELECT foto FROM Usuario WHERE id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $user_id);
    $prepared->execute();
    $prepared->bind_result($foto);
    $prepared->fetch();
    return $foto; // Devuelve los datos binarios de la imagen
}


function obtenerDatosUsuario($id) {
    $conexion = conectar();
    if (!$conexion instanceof mysqli) {
        die("Error: la conexión a la base de datos no es válida.");
    }

    // Definir la consulta SQL
    $sql = "SELECT id, nombre, apellidos, email, tlf, direccion, cp, foto, cVendidos FROM Usuario WHERE id = ?";
    
    // Preparar la consulta
    $prepared = $conexion->prepare($sql);
    if ($prepared === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    // Vincular el parámetro $id
    $prepared->bind_param("s", $id); // "s" indica que el parámetro es una cadena (String)

    // Ejecutar la consulta
    $prepared->execute();

    // Obtener los resultados
    $result = $prepared->get_result();
    
    // Verificar si se obtuvieron resultados
    $data = $result->fetch_assoc();

    if ($data) {
        // Aseguramos que cVendidos no sea nulo y sea un entero
        $cVendidos = isset($data['cVendidos']) ? (int)$data['cVendidos'] : 0;

        // Crear el objeto Usuario con los datos obtenidos
        return new Usuario(
            $data['id'], 
            '', // Contraseña vacía, no se pasa
            $data['direccion'], 
            $data['cp'], 
            $cVendidos, // Asignamos el valor de cVendidos
            $data['tlf'], 
            $data['email'], 
            $data['nombre'], 
            $data['apellidos'],
            $data['foto']
        );
    }
    return null; // No se encontró el Usuario
}


?>









