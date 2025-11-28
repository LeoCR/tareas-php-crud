<?php 

?>
<link rel="stylesheet" href="../../assets/css/home.css">
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Administrador de Tareas</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="color-picker-container">
                <label for="themeColor" class="text-white mb-0">Fondo:</label>
                <select name="colorTema" id="colorTema" class="form-select form-select-sm">
                    <option value="#ffffff">Blanco</option>
                    <option value="#001f3f">Azul Marino</option>
                    <option value="#343a40">Gris Oscuro</option>
                </select>

            </div>
            <a href="#" class="nav-link">Inicio</a>
            <a href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/php/tareas/listar_tareas.php" class="nav-link">Tareas</a>
            <a href="/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/php/login/logout.php" class="nav-link">Cerrar sesión</a>

        </div>
    </div>
</nav>