<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../index.php");
    exit;
}

$secciones = $pdo->query("SELECT * FROM secciones")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $seccion_id = $_POST["seccion_id"];
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];

    $nombres = $_POST["nombre"];
    $apellidos = $_POST["apellido"];

    for ($i = 0; $i < count($nombres); $i++) {

        if (!empty($nombres[$i]) && !empty($apellidos[$i])) {

            $stmt = $pdo->prepare("
                INSERT INTO alumnos (apellido, nombre, anio_id, modalidad_id, seccion_id)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $apellidos[$i],
                $nombres[$i],
                $anio_id,
                $modalidad_id,
                $seccion_id
            ]);
        }
    }

    $_SESSION["success"] = "Alumnos registrados correctamente";
    header("Location: registro_masivo.php");
    exit;
}
?>

<?php
require_once "../../config/database.php";

$anios = $pdo->query("SELECT * FROM anios ORDER BY nombre")->fetchAll();
$modalidades = $pdo->query("SELECT * FROM modalidades ORDER BY nombre")->fetchAll();
$secciones = $pdo->query("SELECT * FROM secciones ORDER BY nombre")->fetchAll();
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="content">
<div class="card">

<h2><i class="bi bi-file-earmark-text-fill"></i> Registro de sección</h2>

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

<hr>

<h4>Alumnos/as</h4>

<div id="contenedor-alumnos">

    <div class="fila-alumno">

        <div class="floating-group">
            <input type="text" name="apellido[]" placeholder=" " required>
            <label>Apellido</label>
        </div>

        <div class="floating-group">
            <input type="text" name="nombre[]" placeholder=" " required>
            <label>Nombre</label>
        </div>

    </div>

</div>

<button type="button" id="agregarAlumno" class="btn">
    <i class="bi bi-person-fill-add"></i> Agregar alumno
</button>

<button type="submit">Guardar Alumnos</button>

</form>

</div>
</div>

<script>
document.getElementById("agregarAlumno").addEventListener("click", function() {

    const contenedor = document.getElementById("contenedor-alumnos");

    const nuevaFila = document.createElement("div");
    nuevaFila.classList.add("fila-alumno");

    nuevaFila.innerHTML = `
        <div class="floating-group">
            <input type="text" name="apellido[]" placeholder=" " required>
            <label>Apellido</label>
        </div>

        <div class="floating-group">
            <input type="text" name="nombre[]" placeholder=" " required>
            <label>Nombre</label>
        </div>
    `;

    contenedor.appendChild(nuevaFila);
});
</script>

<?php include "../layout/footer.php"; ?>
