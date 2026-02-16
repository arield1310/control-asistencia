<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

$alumno = null;
$resumen = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nie = $_POST["nie"];
    $anio = $_POST["anio"];

    // Buscar alumno por NIE
    $stmtAlumno = $pdo->prepare("
        SELECT alumnos.*, 
               anios.nombre AS anio_nombre,
               modalidades.nombre AS modalidad_nombre,
               secciones.nombre AS seccion_nombre
        FROM alumnos
        JOIN anios ON alumnos.anio_id = anios.id
        JOIN modalidades ON alumnos.modalidad_id = modalidades.id
        JOIN secciones ON alumnos.seccion_id = secciones.id
        WHERE alumnos.nie = :nie
        AND alumnos.activo = TRUE
    ");

    $stmtAlumno->execute(["nie" => $nie]);
    $alumno = $stmtAlumno->fetch(PDO::FETCH_ASSOC);

    if ($alumno) {

        $stmtResumen = $pdo->prepare("
            SELECT 
                SUM(CASE WHEN estado = 'A' THEN 1 ELSE 0 END) AS asistencias,
                SUM(CASE WHEN estado = 'T' THEN 1 ELSE 0 END) AS tardanzas,
                SUM(CASE WHEN estado = 'FP' THEN 1 ELSE 0 END) AS permiso,
                SUM(CASE WHEN estado = 'FI' THEN 1 ELSE 0 END) AS sin_permiso,
                COUNT(id) AS total
            FROM asistencia
            WHERE alumno_id = :alumno_id
            AND EXTRACT(YEAR FROM fecha) = :anio
        ");

        $stmtResumen->execute([
            "alumno_id" => $alumno["id"],
            "anio" => $anio
        ]);

        $resumen = $stmtResumen->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

<h2>Reporte Anual por NIE</h2>

<form method="POST">

    <div class="floating-group">
        <input type="text" name="nie" required placeholder=" ">
        <label>Ingrese NIE</label>
    </div>

    <div class="floating-group">
        <select name="anio" required>
            <option value="" disabled selected hidden></option>
            <?php 
            $anio_actual = date("Y");
            for ($i = $anio_actual; $i >= $anio_actual - 5; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>
        <label>Año</label>
    </div>

    <button type="submit">Buscar</button>

</form>

<hr>

<?php if ($alumno): ?>

    <div class="card" style="margin-top:20px;">

        <h3><?= $alumno["apellido"] . " " . $alumno["nombre"] ?></h3>
        <p><strong>NIE:</strong> <?= $alumno["nie"] ?></p>
        <p><strong>Sección:</strong> 
            <?= $alumno["anio_nombre"] ?> - 
            <?= $alumno["modalidad_nombre"] ?> - 
            <?= $alumno["seccion_nombre"] ?>
        </p>

        <hr>

        <p><strong>Asistencias:</strong> <?= $resumen["asistencias"] ?? 0 ?></p>
        <p><strong>Tardanzas:</strong> <?= $resumen["tardanzas"] ?? 0 ?></p>
        <p><strong>Faltas con Permiso:</strong> <?= $resumen["permiso"] ?? 0 ?></p>
        <p><strong>Faltas sin Permiso:</strong> <?= $resumen["sin_permiso"] ?? 0 ?></p>

    </div>

<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>

    <p>No se encontró ningún alumno con ese NIE.</p>

<?php endif; ?>

</div>
</div>

<?php include "../layout/footer.php"; ?>
