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


try{
    $usuarioId = $_SESSION['id'] ?? null;
    if (isset($_POST['task']) && isset($usuarioId)) {
        $success = true;
        $tareaId = intval($_POST['task']);

        $stmt = $mysqli->prepare("DELETE FROM tareaUsuario WHERE ID = ? AND UsuarioID = ?");
        if ($stmt === false) {
            $errors[] = "Error al preparar la consulta: " . $mysqli->error;
            $success = false;
        } else {
            // Pass variables by reference and use the correct user ID in the second placeholder
            $stmt->bind_param("ii", $tareaId, $usuarioId);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        if ($usuarioId === null) {
            $errors[] = "El ID del Usuario es requerido";
        }
        if (empty($_POST['task'])) {
            $errors[] = "El ID de la Tarea es requerido";
        }
    }
}
catch (Exception $e) {
    $mysqli->rollback();
    $errors[] = $e->getMessage();
    $success = false;
}


cerrarConexion($mysqli);
$data = [
    'success' => $success && empty($errors),
    'message' => ($success && empty($errors))
        ? 'La Tarea fue eliminada exitosamente'
        : 'Error al eliminar la tarea.',
    'error'   => empty($errors) ? null : $errors,
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

?>