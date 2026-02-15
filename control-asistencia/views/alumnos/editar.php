<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: listado.php");
    exit;
}

$id = $_GET["id"];

/* Obtener datos del alumno */
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = :id");
$stmt->execute(["id" => $id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo "Alumno no encontrado.";
    exit;
}

/* Obtener listas */
$anios = $pdo->query("SELECT * FROM anios")->fetchAll(PDO::FETCH_ASSOC);
$modalidades = $pdo->query("SELECT * FROM modalidades")->fetchAll(PDO::FETCH_ASSOC);
$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);

/* Guardar cambios */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];
    $seccion_id = $_POST["seccion_id"];
    $activo = isset($_POST["activo"]) ? 1 : 0;

    $update = $pdo->prepare("
        UPDATE alumnos SET
            nombre = :nombre,
            apellido = :apellido,
            anio_id = :anio,
            modalidad_id = :modalidad,
            seccion_id = :seccion,
            activo = :activo
        WHERE id = :id
    ");

    $update->execute([
        "nombre" => $nombre,
        "apellido" => $apellido,
        "anio" => $anio_id,
        "modalidad" => $modalidad_id,
        "seccion" => $seccion_id,
        "activo" => $activo,
        "id" => $id
    ]);

    header("Location: listado.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Editar Alumno</h2>

    <form method="POST">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $alumno['nombre'] ?>" required>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $alumno['apellido'] ?>" required>

        <label>Año:</label>
        <select name="anio_id" required>
            <?php foreach ($anios as $anio): ?>
                <option value="<?= $anio['id'] ?>"
                    <?= $anio['id'] == $alumno['anio_id'] ? "selected" : "" ?>>
                    <?= $anio['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Modalidad:</label>
        <select name="modalidad_id" required>
            <?php foreach ($modalidades as $modalidad): ?>
                <option value="<?= $modalidad['id'] ?>"
                    <?= $modalidad['id'] == $alumno['modalidad_id'] ? "selected" : "" ?>>
                    <?= $modalidad['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Sección:</label>
        <select name="seccion_id" required>
            <?php foreach ($secciones as $seccion): ?>
                <option value="<?= $seccion['id'] ?>"
                    <?= $seccion['id'] == $alumno['seccion_id'] ? "selected" : "" ?>>
                    <?= $seccion['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>
            <input type="checkbox" name="activo"
                <?= $alumno['activo'] ? "checked" : "" ?>>
            Alumno Activo
        </label>

        <br><br>

        <button type="submit">Guardar Cambios</button>
        <a href="listado.php">Cancelar</a>

    </form>
</div>

</body>
</html>
