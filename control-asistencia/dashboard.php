<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);
$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="content">

    <!-- Tarjeta Bienvenida -->
    <div class="card">
        <h2 class="titulo-principal">
            <i class="bi bi-speedometer2"></i> Sistema de Control de Asistencia y Secciones
        </h2>
        <p class="bienvenida">
            Bienvenido, <strong><?php echo $_SESSION["usuario"]; ?></strong>
        </p>
    </div>

    <!-- Accesos rápidos -->
    <div class="card">
        <h3 class="subtitulo">Accesos Rápidos</h3>

        <div class="acciones-grid">
            <a href="views/alumnos/listado.php" class="accion-card">
                <i class="bi bi-people-fill"></i>
                <span>Ver Alumnos</span>
            </a>

            <a href="views/reportes/reporte_mensual.php" class="accion-card">
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Reporte Mensual</span>
            </a>

            <a href="/views/reportes/reporte_anual.php" class="accion-card">
                <i class="bi bi-person-badge-fill"></i>
                <span>Reporte Anual</span>
            </a>
        </div>
    </div>

    <!-- Formulario Asistencia -->
    <div class="card form-card">
        <h3 class="subtitulo">
            <i class="bi bi-check2-square"></i> Registrar Asistencia
        </h3>

        <form method="POST" action="views/asistencia/registrar.php" class="form-grid">

            <div class="floating-group">
                <select name="anio_id" required>
                    <option value="" disabled selected hidden></option>
                    <?php foreach ($anios as $anio): ?>
                        <option value="<?= $anio['id'] ?>">
                            <?= $anio['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Año</label>
            </div>

            <div class="floating-group">
                <select name="modalidad_id" required>
                    <option value="" disabled selected hidden></option>
                    <?php foreach ($modalidades as $modalidad): ?>
                        <option value="<?= $modalidad['id'] ?>">
                            <?= $modalidad['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Modalidad</label>
            </div>

            <div class="floating-group">
                <select name="seccion_id" required>
                    <option value="" disabled selected hidden></option>
                    <?php foreach ($secciones as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= $s['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Sección</label>
            </div>

            <div class="form-boton">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-circle-fill"></i> Validar
                </button>
            </div>

        </form>
    </div>

</div>


<?php include "views/layout/footer.php"; ?>
