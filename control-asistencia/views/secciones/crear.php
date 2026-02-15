<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $_POST["nombre"];
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];

    $stmt = $pdo->prepare("
        INSERT INTO secciones (nombre, anio_id, modalidad_id)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$nombre, $anio_id, $modalidad_id]);

    $_SESSION["success"] = "Sección creada correctamente";
    header("Location: crear.php");
    exit;
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

<h2><i class="bi bi-plus-circle-fill"></i> Crear Nueva Sección</h2>

<?php if (isset($_SESSION["success"])): ?>
    <p style="color:green;">
        <?= $_SESSION["success"]; unset($_SESSION["success"]); ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Nombre de Sección (Ej: A, B, C):</label>
    <input type="text" name="nombre" required>

    <label>Año:</label>
    <select name="anio_id" required>
        <option value="">Seleccione</option>
        <?php foreach ($anios as $a): ?>
            <option value="<?= $a['id'] ?>"><?= $a['nombre'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Modalidad:</label>
    <select name="modalidad_id" required>
        <option value="">Seleccione</option>
        <?php foreach ($modalidades as $m): ?>
            <option value="<?= $m['id'] ?>"><?= $m['nombre'] ?></option>
        <?php endforeach; ?>
    </select>

    <br><br>
    <button type="submit">Crear Sección</button>

</form>

</div>
</div>

<?php include "../layout/footer.php"; ?>
