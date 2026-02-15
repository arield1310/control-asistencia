<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../dashboard.php");
    exit;
}

$anio_id = $_POST["anio_id"];
$modalidad_id = $_POST["modalidad_id"];
$seccion_id = $_POST["seccion_id"];

$stmt = $pdo->prepare("
    SELECT * FROM alumnos
    WHERE anio_id = :anio
    AND modalidad_id = :modalidad
    AND seccion_id = :seccion
    AND activo = TRUE
    ORDER BY apellido
");

$stmt->execute([
    "anio" => $anio_id,
    "modalidad" => $modalidad_id,
    "seccion" => $seccion_id
]);

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fecha = date("Y-m-d");
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

<h2 class="titulo-principal">Registro de Asistencia</h2>
<p class="bienvenida">Fecha: <strong><?= $fecha ?></strong></p>

<?php if (count($alumnos) == 0): ?>

    <div class="alerta-amarilla">
        No hay alumnos registrados en esta sección.
    </div>

    <br>
    <a href="../../dashboard.php" class="btn">Volver</a>

<?php else: ?>

<form method="POST" action="../../controllers/guardar_asistencia.php">
    <input type="hidden" name="fecha" value="<?= $fecha ?>">

    <table>
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Asistió</th>
                <th>Tarde</th>
                <th>F. Permiso</th>
                <th>F. Sin Permiso</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($alumno["apellido"] . ", " . $alumno["nombre"]) ?>
                </td>

                <td style="text-align:center;">
                    <input type="radio" name="estado[<?= $alumno["id"] ?>]" value="A" required>
                </td>

                <td style="text-align:center;">
                    <input type="radio" name="estado[<?= $alumno["id"] ?>]" value="T">
                </td>

                <td style="text-align:center;">
                    <input type="radio" name="estado[<?= $alumno["id"] ?>]" value="FP">
                </td>

                <td style="text-align:center;">
                    <input type="radio" name="estado[<?= $alumno["id"] ?>]" value="FI">
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <br>
    <button type="submit">Guardar Asistencia</button>

</form>

<?php endif; ?>

</div>
</div>

<?php include "../layout/footer.php"; ?>