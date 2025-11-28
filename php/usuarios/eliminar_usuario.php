<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conexionBD.php';

$mysqli = abrirConexion();

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    $stmt = $mysqli->prepare('DELETE FROM usuarios WHERE id = ?');

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}

cerrarConexion($mysqli);
header('Location: listar_usuarios.php');
exit();
