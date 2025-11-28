<?php
function abrirConexion(){
    $host = "localhost";
    $user = "root";
    $password = "";
    $db="practica_tareas";
    $port= 3306;

    $mysqli = new mysqli($host,$user,$password,$db, $port);

    if($mysqli->connect_errno){
       throw new Exception("Error de conexion", $mysqli->connect_error);
    }
    
    $mysqli->set_charset("utf8mb4");
    return $mysqli;
}

function cerrarConexion($mysqli){
    $mysqli->close();
}

abrirConexion();