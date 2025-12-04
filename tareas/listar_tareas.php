<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if(!isset($_SESSION['id'])){
    header("Location: ../login.php");
    exit();
}
include '../php/conexionBD.php';


$mysqli = abrirConexion();

$sql = 'SELECT `ID`, `UsuarioID`, `TareaNombre`, `Descripcion`, `Estado`, `urlImagen`, `FechaCreacion`, `FechaActualizacion` FROM tareaUsuario WHERE UsuarioID = ?';

$tareasDelUsuario= $mysqli->prepare($sql);

$tareasDelUsuario->bind_param("s", $_SESSION['id']);
$tareasDelUsuario->execute();
$resultado = $tareasDelUsuario->get_result();


/**
 * Retorna el nombre del Estado
 */
function getNombreDelEstado($id){
        $conexion = abrirConexion();
        $sqlEstado = "SELECT ID, Nombre from estados WHERE ID = ?";

        $smtEstado= $conexion->prepare($sqlEstado);

        if(!$smtEstado){
            return $id;
        }

        $smtEstado->bind_param("s", $id);
        $smtEstado->execute();
        $result = $smtEstado->get_result()->fetch_assoc();

        cerrarConexion($conexion);

        return $result['Nombre'];
}
cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/css/home.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Listado de Tareas</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</head>

<body>
    <?php include '../php/componentes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <div class="d-flex justify-content-between mb-5">
                <h3>Tareas Registradas</h3>
                <a href="agregar_tarea.php" class="btn btn-success">+ Agregar Tarea</a>
            </div>
            <table id="tabla" class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Tarea</th>
                        <th>Descripcion</th>
                        <th>Estado</th>
                        <th>Fecha de Creacion</th>
                        <th>Fecha de Actualizacion</th>
                        <th>URl Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado) {
                        while ($fila = $resultado->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['ID']); ?></td>
                            <td><?= htmlspecialchars($fila['TareaNombre']); ?></td>
                            <td><?= htmlspecialchars($fila['Descripcion']); ?></td>
                            <td><?= htmlspecialchars(getNombreDelEstado($fila['Estado'])); ?></td>
                            <td><?= htmlspecialchars($fila['FechaCreacion']); ?></td>
                            <td><?= htmlspecialchars($fila['FechaActualizacion']); ?></td>
                            <td> <img class="w-100 h-100 object-fit-cover" src="<?php echo $fila['urlImagen']; ?>" alt="Tarea <?php echo $fila['TareaNombre']; ?>"></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/tareas/editar_tarea.php?task=<?= urlencode($fila['ID']); ?>" class="btn btn-secondary">Editar</a>
                                    <a  data-task-id="<?= urlencode($fila['ID']); ?>" href="#eliminar-<?= urlencode($fila['ID']); ?>" class="btn btn-danger btn-eliminar-tarea">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } // EndIf
                    ?>
                </tbody>

            </table>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer async>
        $(document).ready(() => {
            $('#tabla').dataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            })
        })
    </script>
    <script src="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/js/eliminar-tarea.js" defer></script>
</body>

</html>