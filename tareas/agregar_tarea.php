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
<link rel="stylesheet" href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/css/home.css"/>
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
                            <h4 class="mb-0">Agregar Nueva Tarea</h4>
                        </div>

                        <div class="card-body">

                            <form id="frmTarea" novalidate>
                                <input id="usuarioId" type="hidden" readonly name="usuarioId" class="d-none" value="<?php echo $_SESSION['id']; ?>"/>
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
                                    <button id="save-task" class="btn btn-primary">Guardar Tarea</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/js/agregar-tarea.js" defer> </script>
</body>

</html>