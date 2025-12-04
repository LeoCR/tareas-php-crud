<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}
include 'php/conexionBD.php';


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
    <title>Tareas</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</head>

<body>
    <?php include 'php/componentes/navbar.php'; ?>
    <div class="container">
        <h1 class="h4 m-3">Tareas del usuario</h1>
        <div class="row g-4">
            <?php
            if ($resultado && $resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()):
                    $titulo = $fila['TareaNombre'];
                    $tituloMostrar = (mb_strlen($titulo) > 50) ? mb_substr($titulo, 0, 50) . '...' : $titulo;
                    $fechaReferencia = !empty($fila['FechaActualizacion']) ? $fila['FechaActualizacion'] : $fila['FechaCreacion'];
                    $fechaMostrar = $fechaReferencia ? date('d/m/Y', strtotime($fechaReferencia)) : 'Sin fecha';
            ?>
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow rounded-4 overflow-hidden">
                    <div class="ratio ratio-16x9 bg-light">
                        <?php if (!empty($fila['urlImagen'])): ?>
                            <img
                                src="<?= htmlspecialchars($fila['urlImagen']); ?>"
                                class="w-100 h-100 object-fit-cover"
                                alt="Imagen <?= htmlspecialchars($fila['Descripcion']); ?>"
                            >
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center text-muted fw-semibold">
                                Sin imagen
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold">
                                <?= htmlspecialchars(getNombreDelEstado($fila['Estado'])); ?>
                            </span>
                            <small class="text-muted"><?= htmlspecialchars($fechaMostrar); ?></small>
                        </div>
                        <h5 class="card-title text-truncate"><?= htmlspecialchars($tituloMostrar); ?></h5>
                        <p class="card-text text-muted small mb-0"><?= htmlspecialchars($fila['Descripcion']); ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-flex gap-2">
                            <a href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/tareas/editar_tarea.php?task=<?= $fila['ID']; ?>" class="btn btn-sm btn-primary flex-fill">
                                Ver
                            </a>
                            <a data-task-id="<?= urlencode($fila['ID']); ?>" href="#eliminar-<?= $fila['ID']; ?>" class="btn btn-eliminar-tarea btn-sm btn-danger flex-fill" data-tarea-id="<?= $fila['ID']; ?>">
                                Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
            } else {
                ?>
                <p>No hay Tareas Creadas, favor crear una nueva Tarea.<p>
                    <a class="btn btn-danger" href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/tareas/agregar_tarea.php">Crear mi Primer Tarea</a>
                <?php
            }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/js/eliminar-tarea.js" defer></script>       
    <script defer async>
        $(document).ready(() => {
            $('#tabla').dataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            })
        })
    </script>
</body>

</html>
