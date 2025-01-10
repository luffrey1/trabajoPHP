<?php
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Vehiculo.php';
require_once __DIR__ . '/../model/Coche.php';
require_once __DIR__ . '/../model/Moto.php';


function conectar() {
    $server = "127.0.0.1"; // localhost
    $user = "root";
    $pass = "Sandia4you"; // Sandia4you/1234
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
        $preparedUsuario->close();
        $conexion->close();
        die("Error: El vendedor no existe en la base de datos.");
    }

    $preparedUsuario->close();

    // Insertar los datos en la tabla vehiculo
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

    // Vincular los parámetros excepto el BLOB
    $null = null; // Placeholder para el campo de imagen
    $preparedStatement->bind_param(
        'ssssdiisisb',
        $tipo, $matricula, $color, $combustible, $precio, $cv, 
        $n_puertas, $carroceria, $airbag, $vendedor, $null
    );

    // Enviar el contenido del BLOB
    if (!empty($imagen)) {
        $preparedStatement->send_long_data(10, $imagen); // Índice 10 corresponde a 'foto'
    }

    // Ejecutar la consulta y verificar
    if (!$preparedStatement->execute()) {
        $preparedStatement->close();
        $conexion->close();
        die("Error al insertar el vehículo: " . $preparedStatement->error);
    }

    // Guardar los datos en el objeto Coche (POO)
    $coche->setMatricula($matricula);
    $coche->setColor($color);
    $coche->setCombustible($combustible);
    $coche->setPrecio($precio);
    $coche->setCaballos($cv);
    $coche->setPuertas($n_puertas);
    $coche->setCarroceria($carroceria);
    $coche->setAirbag($airbag);
    $coche->setVendedor($coche->getVendedor()); // Mantiene el vendedor original
    $coche->setImagen($imagen); // Guardar la imagen también

    // Liberar recursos
    $preparedStatement->close();
    $conexion->close();

    return true;
}



function insertarMoto($moto){
    $conexion = conectar();
    
    // Verificar que el vendedor existe en la tabla Usuario
    $sqlUsuario = "SELECT id FROM Usuario WHERE id = ?";
    $preparedUsuario = $conexion->prepare($sqlUsuario);
    $user_id = $moto->getVendedor()->getId(); // Obtener el id del vendedor (Usuario)
    $preparedUsuario->bind_param("s", $user_id);
    $preparedUsuario->execute();
    $result = $preparedUsuario->get_result();

    if ($result->num_rows == 0) {
        $preparedUsuario->close();
        $conexion->close();
        die("Error: El vendedor no existe en la base de datos.");
    }
    $preparedUsuario->close();

    // Insertar datos en la tabla vehiculo
    $sql = "INSERT INTO vehiculo (tipo, matricula, color, combustible, precio, cc, tipo_moto, baul, vendedor,foto)
            VALUES (?,?,?,?,?,?,?,?,?,?)";

    $preparedStatement = $conexion->prepare($sql);

    // Obtener valores del objeto
    $tipo = 'm'; 
    $matricula = $moto->getMatricula();
    $color = $moto->getColor();
    $combustible = $moto->getCombustible();
    $precio = $moto->getPrecio();
    $cc = $moto->getCilindrada();
    $tipo_moto = $moto->getTipo_m();
    $baul = $moto->getBaul();
    $vendedor = $moto->getVendedor()->getId();
    $imagen = $moto->getImagen();
    $null = null; // Placeholder para el campo de imagen

    // Verificar que los valores están correctamente pasados

    $preparedStatement->bind_param(
        'ssssdisisb', 
        $tipo, 
        $matricula, 
        $color, 
        $combustible, 
        $precio, 
        $cc, 
        $tipo_moto, 
        $baul, 
        $vendedor, 
        $null
    );
    


    if (!empty($imagen)) {
        $preparedStatement->send_long_data(9, $imagen); 
    }

   
    // Ejecutar la consulta y verificar
    if (!$preparedStatement->execute()) {
        echo "Error al insertar vehículo: " . $preparedStatement->error;
        $preparedStatement->close();
        $conexion->close();
        return false;
    }

    // Guardar datos en el objeto
    $moto->setMatricula($matricula);
    $moto->setColor($color);
    $moto->setCombustible($combustible);
    $moto->setPrecio($precio);
    $moto->setCilindrada($cc);
    $moto->setTipo_m($tipo_moto);
    $moto->setBaul($baul);
    $moto->setVendedor($moto->getVendedor());
    $moto->setImagen($imagen);

    // Liberar recursos
    $preparedStatement->close();
    $conexion->close();

    return true;
}

// v: esta tabla es para almacenar todos los vehículos comprados, por el usuario que fueron comprados,
// su vendedor y la fecha en que fueron comprados. de esta tabla se puede sacar la cantidad de coches
// comprados o vendidos por un usuario tb, a través de un SELECT.
function crearTablaVenta(){
    $c = conectar();

    $sql = "CREATE TABLE IF NOT EXISTS venta(
        codigo_venta VARCHAR(50) PRIMARY KEY,
        id_vehiculo VARCHAR(50) NOT NULL,
        id_comprador VARCHAR(50) NOT NULL,
        id_vendedor VARCHAR(50) NOT NULL,
        fecha_venta DATE NOT NULL,
        FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(matricula),
        FOREIGN KEY (id_vendedor) REFERENCES Usuario(id),
        FOREIGN KEY (id_comprador) REFERENCES Usuario(id)

    )";

    $c->query($sql);


}
//esta función será para insertas una venta. Lo que podemos hacer es en el catalogo poner botones en cada coche que pnga
// "comprar" y que por ahora así se realice la compra. Lo de las tarjetas a lo mejor se podría hacer más tarde
//pero por ahora que sea algo así, de forma simbolica.
function nuevaVenta($venta){
    $c = conectar();

    $sql = "INSERT INTO venta (codigo_venta, id_vehiculo, id_comprador, id_vendedor, fecha_venta) VALUES
            (?,?,?,?,?)";      
            
    $pS = $c->prepare($sql);

    $codigo_venta = uniqid('v-', true);
    $id_vehiculo = $venta->getIdVehiculo();
    $id_comprador = $venta->getIdComprador();
    $id_vendedor = $venta->getIdVendedor();
    $fecha_venta = $venta->getFechaVenta();

    $pS->bind_param('sssss', $codigo_venta, $id_vehiculo, $id_comprador, $id_vendedor, $fecha_venta);

    return $pS->execute();

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
    if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] == 0) {
        $datos = file_get_contents($_FILES[$campo]['tmp_name']);
        return ['datos' => $datos];
    }
    return ['error' => 'Error al procesar la imagen.'];
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

    $sql = "SELECT foto FROM vehiculo WHERE matricula = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();

    $stmt->close();
    $conexion->close();

    return $foto ? $foto : null;
}



function obtenerDatosUsuario($id) {
    $conexion = conectar();

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
            '', // No se devuelve la contraseña
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

function mostrarVehiculos($pagina = 1, $vehiculos_por_pagina = 10) {
    $conexion = conectar();

    // Calcular el punto de inicio de la consulta para la paginación
    $inicio = ($pagina - 1) * $vehiculos_por_pagina;

    // Obtener los filtros de la URL
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
    $precio = isset($_GET['precio']) ? $_GET['precio'] : '';
    $color = isset($_GET['color']) ? $_GET['color'] : '';
    $cv = isset($_GET['cv']) ? $_GET['cv'] : '';
    $carroceria = isset($_GET['carroceria']) ? $_GET['carroceria'] : '';
    $combustible = isset($_GET['combustible']) ? $_GET['combustible'] : '';
    $n_puertas = isset($_GET['n_puertas']) ? $_GET['n_puertas'] : '';
    $airbag = isset($_GET['airbag']) ? $_GET['airbag'] : '';
    $cc = isset($_GET['cc']) ? $_GET['cc'] : '';
    $tipo_moto = isset($_GET['tipo_moto']) ? $_GET['tipo_moto'] : '';
    $baul = isset($_GET['baul']) ? $_GET['baul'] : '';

    // Construir la consulta SQL con los filtros
    $sql = "SELECT tipo,matricula, color, combustible, precio, cv, n_puertas, carroceria, airbag, vendedor, foto 
            FROM vehiculo 
            WHERE 1=1"; // Comienza la consulta con un WHERE siempre verdadero

    // Filtrar por tipo de vehículo
    if ($tipo) {
        $sql .= " AND tipo = '$tipo'";
    }

    // Filtrar por precio
    if ($precio) {
        $sql .= " AND precio <= $precio";
    }

    // Filtrar por color
    if ($color) {
        $sql .= " AND color LIKE '%$color%'";
    }

    // Filtrar por caballos
    if ($cv) {
        $sql .= " AND cv >= $cv";
    }

    // Filtrar por carrocería
    if ($carroceria) {
        $sql .= " AND carroceria LIKE '%$carroceria%'";
    }

    // Filtrar por combustible
    if ($combustible) {
        $sql .= " AND combustible = '$combustible'";
    }

    // Filtrar por número de puertas
    if ($n_puertas) {
        $sql .= " AND n_puertas = $n_puertas";
    }

    // Filtrar por airbags
    if ($airbag) {
        $sql .= " AND airbag >= $airbag";
    }

    // Filtrar por cilindrada (cc)
    if ($cc) {
        $sql .= " AND cc >= $cc";
    }

    // Filtrar por tipo de moto
    if ($tipo_moto) {
        $sql .= " AND tipo_moto = '$tipo_moto'";
    }

    // Filtrar por baúl
    if ($baul !== '') {
        $sql .= " AND baul = $baul";
    }

    // Añadir la paginación
    $sql .= " LIMIT ?, ?"; // El límite de la consulta

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $inicio, $vehiculos_por_pagina);
    $stmt->execute();

    $result = $stmt->get_result();

    $contador = 0; // Contador para las tarjetas por fila

    echo '<div class="container">'; // Contenedor principal

    while ($row = $result->fetch_assoc()) {
        // Abrir una nueva fila si es la primera tarjeta o cada 3 tarjetas
        if ($contador % 3 === 0) {
            if ($contador > 0) {
                echo '</div>'; // Cerrar la fila anterior
            }
            echo '<div class="row">'; // Abrir una nueva fila
        }

        // Codificar la imagen en base64 si existe
        $imgBase64 = $row['foto'] ? "data:image/jpeg;base64," . base64_encode($row['foto']) : "./database/1.jpeg"; // Ruta de imagen predeterminada

        echo '<div class="col-md-4 mb-4">'; // Columna para cada tarjeta
        echo '    <div class="card">';
        echo '        <img src="' . $imgBase64 . '" class="card-img-top" alt="Imagen del vehículo">';
        echo '        <div class="card-body">';
        echo '            <h5 class="card-title">Matrícula: ' . ($row['matricula']) . '</h5>';
        echo '            <p class="card-text">Color: ' . ($row['color']) . '</p>';
        echo '            <p class="card-text">Combustible: ' . ($row['combustible']) . '</p>';
        echo '            <p class="card-text">Precio: €' . number_format($row['precio'], 2) . '</p>';
        echo '            <p class="card-text">Caballos: ' . ($row['cv']) . '</p>';
        echo '            <p class="card-text">Número de Puertas: ' . ($row['n_puertas']) . '</p>';
        echo '            <p class="card-text">Carrocería: ' . ($row['carroceria']) . '</p>';
        echo '            <p class="card-text">Airbags: ' . ($row['airbag']) . '</p>';
        echo '            <p class="card-text">Vendedor: ' . ($row['vendedor']) . '</p>';
          echo '            <a href="./contactar.php?matricula=' . urlencode($row['matricula']) . '&tipo=' . urlencode($row['tipo']) . '" class="btn btn-primary">Contactar</a>';



        echo '        </div>';
        echo '    </div>';
        echo '</div>';

        $contador++; // Incrementar el contador
    }

    // Cerrar la última fila si no está completa
    if ($contador % 3 !== 0) {
        echo '</div>';
    }

    echo '</div>'; // Cerrar el contenedor principal

    $result->free();
    $conexion->close();
}





function calcularPaginas($vehiculos_por_pagina = 10, $tipo = null, $precio = null, $color = null, $cv = null, $carroceria = null) {
    $conexion = conectar();
    
   
    $sql = "SELECT COUNT(*) AS total FROM vehiculo WHERE 1";
    
   
    if ($tipo) {
        $sql .= " AND tipo = ?";
    }
    if ($precio) {
        $sql .= " AND precio <= ?";
    }
    if ($color) {
        $sql .= " AND color LIKE ?";
    }
    if ($cv) {
        $sql .= " AND cv >= ?";
    }
    if ($carroceria) {
        $sql .= " AND carroceria LIKE ?";
    }
    
    $stmt = $conexion->prepare($sql);
    
    // Vincular los parámetros de la consulta dependiendo de los filtros
    $params = [];
    $types = '';
    
    if ($tipo) {
        $params[] = $tipo;
        $types .= 's';  // tipo es un string
    }
    if ($precio) {
        $params[] = $precio;
        $types .= 'i';  // precio es un int
    }
    if ($color) {
        $params[] = "%$color%";
        $types .= 's';  // color es un string (con comodines)
    }
    if ($cv) {
        $params[] = $cv;
        $types .= 'i';  // cv es un int
    }
    if ($carroceria) {
        $params[] = "%$carroceria%";
        $types .= 's';  // carrocería es un string (con comodines) // por ejemplo sedan deportivo, 
        //busca cualquiera que tenga "sedan"
    }
    
    // Vincular los parámetros
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el número total de vehículos
    $result = $stmt->get_result();
    $total_vehiculos = $result->fetch_assoc()['total'];
    
    // Calcular el número total de páginas
    $total_paginas = ceil($total_vehiculos / $vehiculos_por_pagina);

    return $total_paginas;
}
function obtenerDatosVehiculo($matricula,$tipo) {
    $conexion = conectar();
    if ($tipo == "c") {
        $sql = "SELECT v.matricula, v.color, v.combustible, v.precio, v.cv, v.n_puertas, v.carroceria, v.airbag, 
        v.foto, u.id AS vendedor_id
    FROM vehiculo v
    JOIN Usuario u ON v.vendedor = u.id
    WHERE v.matricula = ?";
    
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data) {
            $vendedor = new Usuario($data['vendedor_id']); 
            // DUDA SETE poner null a contraseña o poner la contraseña aquí 
            return new Coche(
                $data['matricula'], 
                $data['color'], 
                $data['combustible'], 
                $data['precio'], 
                $vendedor,
                $data['n_puertas'], 
                $data['cv'],
                $data['carroceria'],
                $data['airbag'], 
                $data['foto']
            );
        }
     } else if ($tipo == "m") {
            $sql = "SELECT v.matricula, v.color, v.combustible, v.precio, v.cc, v.tipo_moto, v.baul, 
            v.foto, u.id AS vendedor_id
        FROM vehiculo v
        JOIN Usuario u ON v.vendedor = u.id
        WHERE v.matricula = ?";
        
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $matricula);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            if ($data) {
                $vendedor = new Usuario($data['vendedor_id']); 
                // DUDA SETE poner null a contraseña o poner la contraseña aquí 
                return new Moto(
                    $data['matricula'], 
                    $data['color'], 
                    $data['combustible'], 
                    $data['precio'], 
                    $vendedor,
                    $data['cc'], 
                    $data['tipo_moto'],
                    $data['baul'], 
                    $data['foto']
                );
            }
        }
    }
   




function obtenerNombreUsuario($user_id) {
    $conexion = conectar(); 
    $sql = "SELECT nombre FROM Usuario WHERE id = ?";
    $prepared = $conexion->prepare($sql);
    $prepared->bind_param("s", $user_id);
    $prepared->execute();
    $prepared->bind_result($nombre);
    $prepared->fetch();
    return $nombre; 
}










?>