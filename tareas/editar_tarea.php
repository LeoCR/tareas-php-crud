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

$success = false;
$errors = [];

$estadosDisponibles = [];
$estados = $mysqli->query('SELECT `ID`, `Nombre` FROM estados');
if ($estados) {
    while ($estado = $estados->fetch_assoc()) {
        $estadosDisponibles[] = $estado;
    }
    $estados->free();
}

$tareaId = isset($_GET['task']) ? (int)$_GET['task'] : 0;
$usuarioId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

$sqlTareaUsuario = 'SELECT `ID`,`UsuarioID`,`TareaNombre`,`Descripcion`, `Estado`, `urlImagen` FROM `tareaUsuario` WHERE UsuarioID = ? AND ID = ? LIMIT 1';

$tareasDelUsuario= $mysqli->prepare($sqlTareaUsuario);

$tareasDelUsuario->bind_param("ii", $usuarioId, $tareaId);
$tareasDelUsuario->execute();
$resultadoTareas = $tareasDelUsuario->get_result();

$tareas = [];

if ($resultadoTareas) {
    while ($rowTarea = $resultadoTareas->fetch_assoc()) {
        $tareas[] = $rowTarea;
    }
}
if ($resultadoTareas) {
    $resultadoTareas->free();
}

$tareaSeleccionada = $tareas[0] ?? null;

if (!$tareaSeleccionada) {
    header("Location: listar_tareas.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/css/home.css">
</head>

<body>
    <?php include '../php/componentes/navbar.php'; ?>
    
            <?php
            if (!empty($errors)):
                ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php
                        foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                            <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
           
                <div class="container mt-5">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Editar Tarea</h4>
                        </div>

                        <div class="card-body">

                            <form id="frmTarea" novalidate method="post">
                                <input type="hidden" id="usuarioId" name="usuarioId" value="<?php echo (int)$_SESSION['id']; ?>">
                                <input type="hidden" id="tareaId" name="tareaId" value="<?php echo $tareaId; ?>">

                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label class="form-label" for="tareaNombre">Nombre de la tarea</label>
                                    <input
                                        type="text"
                                        id="tareaNombre"
                                        name="tareaNombre"
                                        class="form-control"
                                        required
                                        value="<?php echo htmlspecialchars($tareaSeleccionada['TareaNombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label class="form-label" for="descripcion">Descripción</label>
                                    <textarea
                                        id="descripcion"
                                        name="descripcion"
                                        class="form-control"
                                        rows="3"
                                        required><?php echo htmlspecialchars($tareaSeleccionada['Descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <label class="form-label" for="estado">Estado</label>
                                    <select id="estado" name="estado" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($estadosDisponibles as $estado): ?>
                                            <option
                                                data-id="<?php echo $estado['ID']; ?>"
                                                value="<?php echo $estado['ID']; ?>"
                                                <?php echo ((string)$estado['ID'] === (string)$tareaSeleccionada['Estado']) ? 'selected' : ''; ?>
                                            >
                                                <?php echo htmlspecialchars($estado['Nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Imagen -->
                                <div class="mb-3">
                                    <label class="form-label" for="urlImagen">URL de imagen</label>
                                    <input
                                        type="url"
                                        id="urlImagen"
                                        name="urlImagen"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($tareaSeleccionada['urlImagen'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="listar_tareas.php" class="btn btn-secondary">Cancelar</a>
                                    <button  class="btn btn-primary" id="update-task">Editar Tarea</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/js/editar-tarea.js" defer> </script>
</body>

</html>
