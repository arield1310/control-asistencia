<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}


$reporte = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];
    $seccion_id = $_POST["seccion_id"];
    $mes = $_POST["mes"];
    $anio = $_POST["anio"];
    
    $stmt = $pdo->prepare("
    SELECT 
        alumnos.id,
        alumnos.nombre,
        alumnos.apellido,

        SUM(CASE WHEN asistencia.estado = 'A' THEN 1 ELSE 0 END) AS asistencias,
        SUM(CASE WHEN asistencia.estado = 'T' THEN 1 ELSE 0 END) AS tardanzas,
        SUM(CASE WHEN asistencia.estado = 'FP' THEN 1 ELSE 0 END) AS permiso,
        SUM(CASE WHEN asistencia.estado = 'FI' THEN 1 ELSE 0 END) AS sin_permiso,

        COUNT(asistencia.id) AS total_registros

    FROM alumnos

    LEFT JOIN asistencia 
        ON alumnos.id = asistencia.alumno_id
        AND EXTRACT(MONTH FROM asistencia.fecha) = :mes
        AND EXTRACT(YEAR FROM asistencia.fecha) = :anio

    WHERE alumnos.anio_id = :anio_id
    AND alumnos.modalidad_id = :modalidad_id
    AND alumnos.seccion_id = :seccion_id
    AND alumnos.activo = TRUE

    GROUP BY alumnos.id, alumnos.nombre, alumnos.apellido
    ORDER BY alumnos.apellido
    ");
    
    $stmt->execute([
        "mes" => $mes,
        "anio" => $anio,
        "anio_id" => $anio_id,
        "modalidad_id" => $modalidad_id,
        "seccion_id" => $seccion_id
        ]);
        
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);
$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

    <h2>Reporte Mensual de Asistencia</h2>

    <form method="POST">

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

        <div class="floating-group">
            <select name="mes" required>
                <option value="" disabled selected hidden></option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
            <label>Mes</label>
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
            <label>Año Calendario</label>
        </div>

        <button type="submit">Generar Reporte</button>
    </form>

    <hr>

    <?php if (!empty($reporte)): ?>

        <table class="tabla-asistencia">
            <tr>
                <th>Alumno</th>
                <th>Asistencias</th>
                <th>Tardanzas</th>
                <th>Permisos</th>
                <th>Sin Permiso</th>
                <th>% Asistencia</th>
            </tr>

            <?php foreach ($reporte as $r): 

                $faltas_graves = $r["sin_permiso"] ?? 0;

                $clase = "";
                $mensaje = "";

                if ($faltas_graves >= 15) {
                    $clase = "alerta-roja";
                    $mensaje = "🚨 Riesgo Grave";
                } elseif ($faltas_graves >= 5) {
                    $clase = "alerta-amarilla";
                    $mensaje = "⚠ Advertencia";
                }

                $total = $r["total_registros"] ?? 0;
                $asistencias = $r["asistencias"] ?? 0;

                if ($total > 0) {
                    $porcentaje = round(($asistencias / $total) * 100, 2);
                } else {
                    $porcentaje = 0;
                }

                if ($porcentaje < 60 && $total > 0) {
                    $clase = "alerta-roja";
                    $mensaje = "🚨 Bajo rendimiento";
                }
            ?>
                <tr class="<?= $clase ?>">
                    <td>
                        <?= $r["apellido"] . " " . $r["nombre"] ?>
                        <?php if ($mensaje): ?>
                            <br><strong><?= $mensaje ?></strong>
                        <?php endif; ?>
                    </td>
                        
                    <td><?= $r["asistencias"] ?? 0 ?></td>
                    <td><?= $r["tardanzas"] ?? 0 ?></td>
                    <td><?= $r["permiso"] ?? 0 ?></td>
                    <td><?= $faltas_graves ?></td>
                    <td><?= $porcentaje ?>%</td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</div>
</div>

<?php include "../layout/footer.php"; ?>