<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conexionBD.php';

$mysqli = abrirConexion();

$success = false;
$errors = [];
$estadosPermitidos = ['Pendiente', 'En Progreso', 'Completada', 'Bloqueada', 'Incompleta'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tareaNombre = trim($_POST['tareaNombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $urlImagen = trim($_POST['urlImagen'] ?? '');
 
    // Validaciones
    // if ($tareaID === '') $errors[] = "ID de tarea no válido.";
    if ($tareaNombre === '') $errors[] = "El nombre de la tarea es obligatorio.";
    if ($descripcion === '') $errors[] = "La descripción es obligatoria.";
    if (!in_array($estado, $estadosPermitidos)) $errors[] = "El estado seleccionado no es válido.";
 /*
    if (empty($errors)) {
        // Verificar propiedad antes de actualizar (Doble verificación de seguridad)
        $checkStmt = $mysqli->prepare("SELECT COUNT(*) FROM tareaUsuario WHERE ID = ? AND UsuarioID = ?");
        $checkStmt->bind_param("ii", $tareaID, $userId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result()->fetch_row()[0];
        $checkStmt->close();
 
         if ($checkResult == 0) {
            $errors[] = "Error: No tiene permisos para editar esta tarea o la tarea no existe.";
        } else {
            // Realizar el UPDATE si la propiedad es correcta y los datos son válidos
            $stmt = $mysqli->prepare("UPDATE tareaUsuario SET TareaNombre = ?, Descripcion = ?, Estado = ?, urlImagen = ? WHERE ID = ? AND UsuarioID = ?");
            $stmt->bind_param("ssssii", $tareaNombre, $descripcion, $estado, $urlImagen, $tareaID, $userId);
           
            if ($stmt->execute()) {
                header('Location: listaTareas.php?success=2');
                exit;
            } else {
                $errors[] = "Error al actualizar la tarea: " . $stmt->error;
            }
            $stmt->close();
        }
    }
*/ 
    // Si hubo errores, necesitamos repoblar la variable $tarea para que el formulario se muestre con los datos POST
    if (!empty($errors)) {
         $tarea = [
            'ID' => 1,
            'TareaNombre' => $tareaNombre,
            'Descripcion' => $descripcion,
            'Estado' => $estado,
            'urlImagen' => $urlImagen
        ];
    }


   
    if (empty($errors)) {

        $stmt = $mysqli->prepare("INSERT INTO `tareausuario` ( `UsuarioID`, `TareaNombre`, `Descripcion`, `Estado`, `urlImagen`) VALUES(? , ? , ? , ? , ? )");
        $stmt->bind_param("issis", 1, $tareaNombre, $descripcion, $estado, $urlImagen);


        if (!$stmt->execute()) {
            $errors[] = "Error al insertar el Usuario";
        } else {
            $success = true;
        }
        $stmt->close();

    }
}
cerrarConexion($mysqli);

if ($success) {
    header('Location: listar_tareas.php');
}

$mysqli = abrirConexion();

$estados = $mysqli->query('SELECT `ID`, `Nombre`  from estados');

cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear nueva Tarea</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h3 class=" text-center  mb-1">Agregar Nuevo Usuario</h3>


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
                            <h4 class="mb-0">Agregar Nueva Tarea</h4>
                        </div>

                        <div class="card-body">

                            <form id="frmTarea" novalidate method="post">

                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label class="form-label">Nombre de la tarea </label>
                                    <input type="text" id="tareaNombre" name="tareaNombre" class="form-control"
                                        required>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label class="form-label">Descripción </label>
                                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                                        required></textarea>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <label class="form-label">Estado </label>
                                    <select id="estado" name="estado" class="form-select" required>
                                        <option value="no-seleccionado">Seleccione...</option>
                                        <?php 
                                         while ($fila = $estados->fetch_assoc()):?>
                                         <option data-id="<?php echo $fila['ID']; ?>" value="<?php echo $fila['ID']; ?>"><?php echo $fila['Nombre']; ?></option>
                                         <?php

                                         endwhile;
                                        ?>
                                        
                                    </select>
                                </div>

                                <!-- Imagen -->
                                <div class="mb-3">
                                    <label class="form-label">URL de imagen...</label>
                                    <input type="url" id="urlImagen" name="urlImagen" class="form-control">
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="nombreDelArchivo" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", ()=> {
            document.getElementById("frmTarea")?.addEventListener("submit", async function (e) {
 
    e.preventDefault();
 
    // Obtener valores
    const nombre = document.getElementById("tareaNombre").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const estado = document.getElementById("estado").value;
    const urlImagen = document.getElementById("urlImagen").value.trim();
 
    // Límites
    const maxNombre = 50;
    const maxDescripcion = 255;
    const maxUrl = 200;
 
    // Validaciones
    if (!nombre || !descripcion || !estado) {
        Swal.fire({
            icon: "warning",
            title: "Datos incompletos",
            text: "Debe completar todos los campos obligatorios.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        })
        return;
    }
 
   
    if (nombre.length > maxNombre) {
        Swal.fire({
            icon: "error",
            title: "Nombre demasiado largo",
            text: `El nombre no puede superar los ${maxNombre} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        })
        return;
    }
 
    if (descripcion.length > maxDescripcion) {
        Swal.fire({
            icon: "error",
            title: "Descripción muy larga",
            text: `La descripción no puede superar los ${maxDescripcion} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        })
        return;
    }
 
    if (urlImagen && urlImagen.length > maxUrl) {
        Swal.fire({
            icon: "error",
            title: "URL muy larga",
            text: `La URL no puede superar los ${maxUrl} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        })
        return;
    }
 
 
    if (urlImagen !== "" && !urlImagen.startsWith("http")) {
        Swal.fire({
            icon: "error",
            title: "URL inválida",
            text: "La URL debe iniciar con http o https.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        })
        return;
    }
 
    // Associate the FormData object with the form element
    const formData = new FormData();

    formData.append("tareaNombre" ,nombre);
    formData.append("descripcion" ,descripcion);
    formData.append("estado" ,estado);
    formData.append("urlImagen" ,urlImagen);
    formData.append("userId" ,1);

    try {
        const response = await fetch("http://localhost:8080/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/php/tareas/agregar_tarea.php", {
        method: "POST",
        // Set the FormData instance as the request body
        body: formData,
        });
       const resp = await response.json()
    } catch (e) {
        console.error(e);
    }
 
})
        })
    </script>
</body>

</html>