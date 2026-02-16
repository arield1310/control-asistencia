<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);
$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);

$alumnos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];
    $seccion_id = $_POST["seccion_id"];

    $stmt = $pdo->prepare("
        SELECT alumnos.*, anios.nombre AS anio_nombre,
               modalidades.nombre AS modalidad_nombre,
               secciones.nombre AS seccion_nombre
        FROM alumnos
        JOIN anios ON alumnos.anio_id = anios.id
        JOIN modalidades ON alumnos.modalidad_id = modalidades.id
        JOIN secciones ON alumnos.seccion_id = secciones.id
        WHERE alumnos.anio_id = :anio
        AND alumnos.modalidad_id = :modalidad
        AND alumnos.seccion_id = :seccion
        ORDER BY alumnos.apellido
    ");

    $stmt->execute([
        "anio" => $anio_id,
        "modalidad" => $modalidad_id,
        "seccion" => $seccion_id
    ]);

    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

    <h2>Listado de Alumnos</h2>

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

        <button type="submit">Buscar</button>
    </form>

    <hr>

    <?php if (!empty($alumnos)): ?>

        <table class="tabla-asistencia">
            <tr>
                <th>NIE</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= $alumno["nie"] ?? "—" ?></td>
                    <td><?= $alumno["apellido"] ?></td>
                    <td><?= $alumno["nombre"] ?></td>
                    <td><?= $alumno["activo"] ? "Activo" : "Inactivo" ?></td>
                    <td>
                        <a href="editar.php?id=<?= $alumno['id'] ?>"><i class="bi bi-pencil-fill"></i> Editar</a> |
                        <a href="../../controllers/eliminar_alumno.php?id=<?= $alumno['id'] ?>"
                           onclick="return confirm('¿Seguro que desea eliminar este alumno?')">
                           <i class="bi bi-trash-fill"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>

        <p>No hay alumnos en esta sección.</p>

    <?php endif; ?>

</div>
</div>


<?php include "../layout/footer.php"; ?>
