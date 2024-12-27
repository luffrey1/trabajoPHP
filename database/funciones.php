<?php
include_once("./model/Usuario.php");
include_once("./model/Vehiculo.php");
include_once("./model/Coche.php");
include_once("./model/Moto.php");

function conectar() {
    $server = "127.0.0.1"; // localhost
    $user = "root";
    $pass = "Sandia4you"; // Sandia4you
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


function insertarUsuario($usuario) {
    $conexion = conectar();
    $sql = "INSERT INTO Usuario (id, contra, direccion, CP, cVendidos, tlf, email, nombre, apellidos, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $prepared = $conexion->prepare($sql);
    
    $id = $usuario->getId();
    $pass = password_hash($usuario->getContra(), PASSWORD_DEFAULT); //contraseña cifrada
    $direccion = $usuario->getDireccion();
    $cp = $usuario->getCp();
    $cVendidos = $usuario->getCvendidos();
    $tlf= $usuario->getTlf();
    $email = $usuario->getEmail();
    $nombre = $usuario->getNombre();
    $apellidos = $usuario->getApellidos();
    $imagen = $usuario->getImagen();

    $prepared->bind_param(
        "ssssisssss", 
        $id,
        $pass,          // Se pasa la contraseña cifrada aquí
        $direccion,
        $cp,
        $cVendidos,
        $tlf,
        $email,
        $nombre,
        $apellidos,
        $imagen
    );
    
    $prepared->execute();
}


function crearTablaVehiculo() {
    $conexion = conectar();

    // Crear la tabla Vehiculo
    $sql = "CREATE TABLE IF NOT EXISTS vehiculo (
        tipo ENUM('c', 'm') NOT NULL,
        matricula VARCHAR(50) PRIMARY KEY,
        color VARCHAR(25) NOT NULL,
        combustible VARCHAR(100) NOT NULL,
        precio DECIMAL(8,2) NOT NULL, -- Ajuste para permitir precios más altos
        cv INT DEFAULT NULL, -- coches
        n_puertas INT DEFAULT NULL, -- coches
        carroceria VARCHAR(50) DEFAULT NULL, -- coches
        airbag INT DEFAULT NULL, -- coches
        cc INT DEFAULT NULL, -- motos
        tipo_moto VARCHAR(50) DEFAULT NULL, -- motos
        baul TINYINT(1) DEFAULT NULL, -- motos
        vendedor VARCHAR(50), -- Hace referencia a Usuario.id
        FOREIGN KEY (vendedor) REFERENCES Usuario(id),
        foto LONGBLOB
  
    )";

    // Ejecutar la consulta
    $conexion->query($sql);
}

function insertarCoche($coche) {
    // Conexión a la base de datos
    $conexion = conectar();

    // Verificar que el vendedor existe en la tabla Usuario
    $sqlUsuario = "SELECT id FROM Usuario WHERE id = ?";
    $preparedUsuario = $conexion->prepare($sqlUsuario);
    $user_id = $coche->getVendedor()->getId(); // Obtener el id del vendedor (Usuario)
    $preparedUsuario->bind_param("s", $user_id);
    $preparedUsuario->execute();
    $result = $preparedUsuario->get_result();

    // Si el vendedor no existe, lanzar un error
    if ($result->num_rows == 0) {
        die("Error: El vendedor no existe en la base de datos.");
    }

    // Si el vendedor existe, proceder con la inserción del coche
    $sql = "INSERT INTO vehiculo (tipo, matricula, color, combustible, precio, cv, n_puertas, carroceria, airbag, vendedor, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $preparedStatement = $conexion->prepare($sql);

    // Obtener los datos del coche
    $tipo = 'c';
    $matricula = $coche->getMatricula();
    $color = $coche->getColor();
    $combustible = $coche->getCombustible();
    $precio = $coche->getPrecio();
    $cv = $coche->getCaballos();
    $n_puertas = $coche->getPuertas();
    $carroceria = $coche->getCarroceria();
    $airbag = $coche->getAirbag();
    $vendedor = $coche->getVendedor()->getId(); // Obtener el id del vendedor
    $imagen = $coche->getImagen();
    // Ahora pasamos los parámetros de manera correcta
    $preparedStatement->bind_param(
        'ssssdiisiss',
        $tipo, $matricula, $color, $combustible, $precio, $cv, 
        $n_puertas, $carroceria, $airbag, $vendedor, $imagen
    );

    return $preparedStatement->execute();
}


function insertarMoto($moto){

    $c = conectar();

    // Verificar que el vendedor existe en la tabla Usuario
    $sqlUsuario = "SELECT id FROM Usuario WHERE id = ?";
    $preparedUsuario = $c->prepare($sqlUsuario);
    $user_id = $moto->getVendedor()->getId(); // Obtener el id del vendedor (Usuario)
    $preparedUsuario->bind_param("s", $user_id);
    $preparedUsuario->execute();
    $result = $preparedUsuario->get_result();

    // Si el vendedor no existe, lanzar un error
    if ($result->num_rows == 0) {
        die("Error: El vendedor no existe en la base de datos.");
    }

    $sql = "INSERT INTO
    vehiculo (tipo, matricula, color, combustible, precio, cc, tipo_moto, baul, vendedor)
    VALUES (?,?,?,?,?,?,?,?,?)";

    $preparedStatement=$c->prepare($sql);

    $tipo = 'm';
    $matricula = $moto->getMatricula();
    $color = $moto->getColor();
    $combustible = $moto->getCombustible();
    $precio = $moto->getPrecio();
    $cc = $moto->getCilindrada();
    $tipo_moto = $moto->getTipo_m();
    $baul=$moto->getBaul();
    $vendedor = $moto->getVendedor();

    $preparedStatement->bind_param('ssssdisis', $tipo, $matricula, $color, $combustible,
    $precio, $cc, $tipo_moto, $baul, $vendedor);

    return $preparedStatement->execute();

}
// v: esta tabla es para almacenar todos los vehículos comprados, por el usuario que fueron comprados,
// su vendedor y la fecha en que fueron comprados. de esta tabla se puede sacar la cantidad de coches
// comprados o vendidos por un usuario tb, a través de un SELECT.
function crearTablaVentas(){
    $c = conectar();

    $sql = "CREATE TABLE IF NOT EXISTS ventas(
        codigo_venta VARCHAR(50) PRIMARY KEY,
        id_vehiculo VARCHAR(50) NOT NULL,
        id_vendedor VARCHAR(50) NOT NULL,
        id_comprador VARCHAR(50) NOT NULL,
        fecha_venta DATE NOT NULL,
        FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(matricula),
        FOREIGN KEY (id_vendedor) REFERENCES Usuario(id),
        FOREIGN KEY (id_comprador) REFERENCES Usuario(id)

    )";

    $c->query($sql);


}

//esta función será para insertas una venta. Lo que podemos hacer es en el catalogo poner botones en cada coche que pnga
// "comprar" y que por ahora así se realice la compra. Lo de las tarjetas a lo mejor se podría hacer más tarde
//pero por ahora que sea algo así, de forma simbolica
function nuevaVenta($venta){
    
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

//v:creo que tampoco se utiliza si tenemos la de verificarUsuario
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
       
            $prepared->bind_param("ssssssss", $nombre, $apellidos, $direccion,
            $cp, $tlf, $email, $foto_url, $user_id);
        
        }
    }  else {
        $sql = "UPDATE Usuario
        SET nombre = ?, apellidos = ?, direccion = ?, CP = ?, tlf = ?, email = ?
        WHERE id = ?";

        if ($prepared = $conexion->prepare($sql)) {
       
            $prepared->bind_param("sssssss", $nombre, $apellidos, $direccion,
            $cp, $tlf, $email, $user_id);
        
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
function obtenerImagenVehiculo($matricula) {
    $conexion = conectar(); 
    $sql = "SELECT foto FROM Vehiculo WHERE matricula = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $matricula);
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
// Función para mostrar todos los vehículos con paginación
// Función para mostrar todos los vehículos con paginación usando Cards de Bootstrap
function mostrarVehiculos($pagina = 1, $vehiculos_por_pagina = 10) {
    $conexion = conectar();
    
    // Calcular el punto de inicio de la consulta para la paginación
    $inicio = ($pagina - 1) * $vehiculos_por_pagina;

    // Consulta SQL para obtener los vehículos con límite y desplazamiento
    $sql = "SELECT * FROM vehiculo LIMIT ?, ?";
    $preparedStatement = $conexion->prepare($sql);
    $preparedStatement->bind_param("ii", $inicio, $vehiculos_por_pagina);
    $preparedStatement->execute();

    // Obtener los resultados
    $result = $preparedStatement->get_result();
    
    // Mostrar los vehículos como Cards
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-4">';
        echo '    <div class="card">';
        // $imagen = base64_encode($imagen_Vehiculo);
        echo '        <img src="ruta_de_imagen.jpg" class="card-img-top" alt="Imagen del vehículo">';  // Cambia la imagen si es necesario
        echo '        <div class="card-body">';
        echo '            <h5 class="card-title">Matrícula: ' . $row['matricula'] . '</h5>';
        echo '            <p class="card-text">Color: ' . $row['color'] . '</p>';
        echo '            <p class="card-text">Combustible: ' . $row['combustible'] . '</p>';
        echo '            <p class="card-text">Precio: €' . number_format($row['precio'], 2) . '</p>';
        echo '            <p class="card-text">Caballos: ' . $row['cv'] . '</p>';
        echo '            <p class="card-text">Número de Puertas: ' . $row['n_puertas'] . '</p>';
        echo '            <p class="card-text">Carrocería: ' . $row['carroceria'] . '</p>';
        echo '            <p class="card-text">Airbags: ' . $row['airbag'] . '</p>';
        echo '            <p class="card-text">Vendedor: ' . $row['vendedor'] . '</p>';
        echo '            <a href="#" class="btn btn-primary">Contactar</a>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }

    // Liberar la memoria
    $result->free();
    $conexion->close();
}


// Función para calcular el número total de páginas
function calcularPaginas($vehiculos_por_pagina = 10) {
    $conexion = conectar();
    
    // Obtener el número total de vehículos
    $sql = "SELECT COUNT(*) AS total FROM vehiculo";
    $result = $conexion->query($sql);
    $total_vehiculos = $result->fetch_assoc()['total'];
    
    // Calcular el número total de páginas
    $total_paginas = ceil($total_vehiculos / $vehiculos_por_pagina);

    return $total_paginas;
}


?>