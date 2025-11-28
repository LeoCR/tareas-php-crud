<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conexionBD.php';

$mysqli = abrirConexion();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id === 0){
    header('Location: listar_usuarios.php');
}

$errors = [];


$stmt = $mysqli->prepare('SELECT id,nombre,usuario,correo, fecha_nacimiento, genero FROM usuarios WHERE id = ?');

$stmt->bind_param('i', $id);
$stmt->execute();

$userData = $stmt->get_result()->fetch_assoc();

$stmt->close();

//Consultar Usuario

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']) ?? '';
    $correo = trim($_POST['correo']) ?? '';
    $usuario = trim($_POST['usuario']) ?? '';
    $contrasenna = trim($_POST['contrasenna']) ?? '';
    $fechaNacimiento = trim($_POST['fechaNacimiento']) ?? '';
    $genero = trim($_POST['genero']) ?? '';

    if ($nombre === '') {
        $errors[] = 'Nombre es obligatorio';
    }
    if (filter_var($correo, FILTER_VALIDATE_EMAIL) == '') {
        $errors[] = 'Correo no es válido';
    }

    if ($usuario === '') {
        $errors[] = 'El Usuario es obligatorio';
    }

    if ($contrasenna !== '' && strlen($contrasenna) < 6) {
        $errors[] = 'La Contraseña debe contener al menos 6 caracteres';
    }

    //usuario existe en BD si y solo si no hay errores
    if (empty($errors)) {
        $clave = password_hash($contrasenna, PASSWORD_DEFAULT);

        if($contrasenna !== ''){
            $stmt = $mysqli->prepare("UPDATE usuarios SET nombre = ?, correo = ?, usuario =?, clave =?, fecha_nacimiento =?, genero =? WHERE id=?");
            $stmt->bind_param("ssssssi", $nombre, $correo, $usuario, $clave, $fechaNacimiento, $genero, $id);
        
        }
        else{
            $stmt = $mysqli->prepare("UPDATE usuarios SET nombre = ?, correo = ?, usuario =?,  fecha_nacimiento =?, genero =? WHERE id=?");
            $stmt->bind_param("sssssi", $nombre, $correo, $usuario, $fechaNacimiento, $genero, $id);

        }

        $ok =$stmt->execute();

        if(!$ok) {
           cerrarConexion($mysqli);
           header("Location listar_usuarios.php");
           exit;
        } else {
            $errors[] = "Error al actualizar Usuario";
        }
        $stmt->close();

    }
}
cerrarConexion($mysqli);

if ($success) {
    header('Location: listar_usuarios.php');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h3 class=" text-center text-success mb-4">Agregar Nuevo Usuario</h3>


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
            <form novalidate method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($userData['nombre']) ?>"/>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($userData['correo']) ?>" />
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" value="<?= htmlspecialchars($userData['usuario']) ?>"/>
                </div>

                <div class="mb-3">
                    <label for="contrasenna" class="form-label">Nueva Contraseña(Opcional_</label>
                    <input type="password" name="contrasenna" id="contrasenna" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" value="<?= htmlspecialchars($userData['fecha_nacimiento']) ?>"/>
                </div>

                <div class="mb-3">
                    <label for="genero" class="form-label">Género</label>
                    <select name="genero" id="genero">
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                </div>

                <div class="text-end">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a class="btn btn-danger" href="listar_usuarios.php">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>