<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$response = [
    'mensaje'=> 'Error inesperado',
    'debug'=> 'inicio',
    'status'=> 'error',
];

try {
    include '../conexionBD.php';
    $raw = file_get_contents("php://input");
    $datos = json_decode($raw, true);

    if(!$datos){
        $response['mensaje']= 'Los datos no pudieron ser procesados';
        $response['debug'] = 'json invalido';
        echo json_encode($response);
        exit();
    }

    $usuario = trim($datos['usuario'] ?? '');
    $clave = trim($datos['contrasenna'] ?? '');

    if(!$usuario || !$clave){
        $response['mensaje'] = 'Usuario o contraseña vacios';
        $response['debug'] = 'Campos vacios';
        echo json_encode($response);
        exit();
    }

    $mysqli = abrirConexion();

    $sql = "SELECT id, nombre, usuario, clave from usuarios WHERE usuario = ?";

    $stmt= $mysqli->prepare($sql);

    if(!$stmt){
        $response['mensaje'] = 'Error al prepara la consulta';
        $response['debug'] = 'SQL fallo';
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $response['debug'] = 'consulta ejecutada';


    if($resultado && $resultado->num_rows > 0){
        $fila= $resultado->fetch_assoc();

        if(password_verify($clave, $fila['clave'])){
            $_SESSION['id'] = $fila['id'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['usuario'] = $fila['usuario'];
            
            $response = [
                'status'=> 'ok',
                'nombre'=> $fila['nombre'],
                'debug'=> 'login exitoso',
            ];
        }
        else{
            $response['mensaje'] = 'Contraseña incorrecta';
            $response['debug'] = 'Fallo de contraseña';
        }    
    }else{
        $response['mensaje'] = 'Usuario no encontrado';
        $response['debug'] = 'Usuario no existe';
    }
    cerrarConexion($mysqli);
} catch (\Throwable $th) {
    $response['mensaje'] = 'Sucedio un error al realizar login';
    $response['debug'] = 'Catch exception: '. $th->getMessage();
}
echo json_encode($response);
exit();