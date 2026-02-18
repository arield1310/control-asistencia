<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SESSION["rol"] !== "admin") {
    header("Location: ../../dashboard.php");
    exit;
}

require_once "../../config/database.php";

$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);
$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

    <h2><i class="bi bi-person-fill-add"></i> Registrar Nuevo Alumno</h2>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color:red;">
            <?= $_SESSION["error"]; unset($_SESSION["error"]); ?>
        </p>
    <?php endif; ?>
        
    <?php if (isset($_SESSION["success"])): ?>
        <p style="color:green;">
            <?= $_SESSION["success"]; unset($_SESSION["success"]); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="../../controllers/guardar_alumno.php">

        <div class="floating-group">
            <input type="text" name="nie" placeholder=" " required>
            <label>NIE</label>
        </div>
        
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Apellido:</label>
        <input type="text" name="apellido" required>

        <label>Año:</label>
        <select name="anio_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($anios as $anio): ?>
                <option value="<?= $anio['id'] ?>">
                    <?= $anio['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Modalidad:</label>
        <select name="modalidad_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($modalidades as $modalidad): ?>
                <option value="<?= $modalidad['id'] ?>">
                    <?= $modalidad['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Sección:</label>
        <select name="seccion_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($secciones as $seccion): ?>
                <option value="<?= $seccion['id'] ?>">
                    <?= $seccion['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type="submit">Guardar Alumno</button>
    </form>

</div>
</div>

<?php include "../layout/footer.php"; ?>



