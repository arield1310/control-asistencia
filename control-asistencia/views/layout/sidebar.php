<div class="sidebar">

    <a href="/dashboard.php">
        <i class="bi bi-house-door-fill"></i> Principal
    </a>

    <?php if ($_SESSION["rol"] === "admin"): ?>

        <a href="/views/secciones/crear.php">
            <i class="bi bi-plus-circle-fill"></i> Crear Sección
        </a>

        <a href="/views/alumnos/registro_masivo.php">
            <i class="bi bi-file-earmark-text-fill"></i> Registro Masivo
        </a>

        <a href="/views/alumnos/crear.php">
            <i class="bi bi-person-fill-add"></i> Registrar Alumno
        </a>

    <?php endif; ?>

    <a href="/views/alumnos/listado.php">
        <i class="bi bi-people-fill"></i> Listado
    </a>

    <a href="/views/reportes/reporte_mensual.php">
        <i class="bi bi-clipboard-data-fill"></i> Reporte Mensual
    </a>

    <a href="/views/reportes/reporte_anual.php">
        <i class="bi bi-person-badge-fill"></i> Reporte Anual de Alumno
    </a>

    <a href="/logout.php">
        <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
    </a>

</div>


