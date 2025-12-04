<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conexionBD.php';


$mysqli = abrirConexion();

$resultado = $mysqli->query('SELECT id,nombre,correo,usuario,fecha_nacimiento, genero from usuarios');

cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Listado de Usuarios</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
 
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <div class="d-flex justify-content-between mb-5">
                <h3>Usuarios Registrados</h3>
                <a href="agregar_usuario.php" class="btn btn-success">+ Agregar Usuario</a>
            </div>
            <table id="tabla" class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Genero</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fila = $resultado->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $fila['id']; ?></td>
                            <td><?= htmlspecialchars($fila['nombre']); ?></td>
                            <td><?php echo $fila['correo']; ?></td>
                            <td><?php echo $fila['usuario']; ?></td>
                            <td><?php echo $fila['fecha_nacimiento']; ?></td>
                            <td><?= htmlspecialchars($fila['genero']); ?></td>
                            <td>
                                <div class="d-flex">
                                    <a href="editar_usuario.php?id=<?= $fila['id']; ?>" class="btn btn-secondary">Editar</a>
                                    <a href="eliminar_usuario.php?id=<?= $fila['id']; ?>"
                                        class="btn btn-danger">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(()=>  {
            $('#tabla').dataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            })
        })
    </script>
</body>

</html>