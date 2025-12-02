<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../conexionBD.php");

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nombre = $_POST["nombre"] ?? "";
    $correo = $_POST["correo"] ?? "";
    $usuario = $_POST["usuario"] ?? "";
    $clave = $_POST["clave"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";
    $fecha = $_POST["fecha"] ?? "";
    $genero = $_POST["genero"] ?? "";

    $claveHash = password_hash($clave, PASSWORD_DEFAULT);

    $conexion = abrirConexion();

    $sql = "INSERT INTO usuarios(nombre,correo, usuario, clave, fecha_nacimiento,genero) VALUES(?, ? , ?, ? ,?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("ssssss", $nombre, $correo, $usuario, $claveHash,$fecha, $genero);

    if($stmt->execute()){
        $sqlUser = "SELECT id, nombre, usuario, clave from usuarios WHERE usuario = ?";

        $stmtUserData= $conexion->prepare($sqlUser);

        if(!$stmtUserData){
            $response['mensaje'] = 'Error al obtener informacion del usuario';
            $response['debug'] = 'SQL fallo';
            echo json_encode($response);
            exit();
        }

        $stmtUserData->bind_param("s", $usuario);
        $stmtUserData->execute();
        $resultado = $stmtUserData->get_result();
        if($resultado && $resultado->num_rows > 0){

            $fila= $resultado->fetch_assoc();

            $_SESSION['id'] = $fila['id'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['usuario'] = $fila['usuario'];
            echo "ok";
        }else{
            echo "error: El Usuario no esta disponible. Favor intentar mas tarde.";
        }
    }
    else{
        echo "error:".$conexion->error;
    }

    $stmt->close();

    cerrarConexion($conexion);
}

?>