<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = false;
$errors = [];

include '../php/conexionBD.php';

$mysqli = abrirConexion();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    // Validar sesión
    $usuarioId = $_SESSION['id'] ?? null;
    if ($usuarioId === null) {
        $errors[] = "Usuario inválido.";
    }
    $tareaId = isset($_POST['tareaId']) ? (int)$_POST['tareaId'] : 0;

    // Entrada
    $tareaNombre = trim($_POST['tareaNombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 0;
    $urlImagen = trim($_POST['urlImagen'] ?? '');
 
    // Validaciones
    if ($tareaNombre === '') $errors[] = "El nombre de la tarea es obligatorio.";
    if ($descripcion === '') $errors[] = "La descripción es obligatoria.";
    if ($urlImagen === '' || strlen($urlImagen) > 200) $errors[] = "URL inválida o demasiado larga.";
    if ($estado <= 0) $errors[] = "Estado de la tarea inválido.";
    if($tareaId <= 0) $errors[] = "El ID de la Tarea es incorrecto";

    try{
        if (empty($errors)) {
            // Iniciar transacción
            $mysqli->begin_transaction();

            

            // Cambiar a UPDATE usando la variable @tareaId en el WHERE, manteniendo 5 parámetros
            $stmt = $mysqli->prepare("UPDATE `tareaUsuario` 
                                      SET `UsuarioID` = ?, `TareaNombre` = ?, `Descripcion` = ?, `Estado` = ?, `urlImagen` = ?
                                      WHERE `ID` = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $mysqli->error);
            }

            $stmt->bind_param("issisi", $usuarioId, $tareaNombre, $descripcion, $estado, $urlImagen, $tareaId);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar la tarea: " . $stmt->error);
            } else {
                $success = true;
            }

            $mysqli->commit();
            $stmt->close();
        }
    }
    catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = $e->getMessage();
            $success = false;
    }

    // Respuesta JSON
    $data = [
        'success' => $success && empty($errors),
        'message' => ($success && empty($errors))
            ? 'La Tarea fue editada exitosamente'
            : 'Error al editar tarea.',
        'error'   => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.',
        'error' => ['Se requiere una petición POST.']
    ]);
}

cerrarConexion($mysqli);
?>
