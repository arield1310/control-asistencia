<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../dashboard.php");
    exit;
}

$nie = $_POST["nie"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$anio_id = $_POST["anio_id"];
$modalidad_id = $_POST["modalidad_id"];
$seccion_id = $_POST["seccion_id"];

$stmt = $pdo->prepare("INSERT INTO alumnos 
(nie, nombre, apellido, anio_id, modalidad_id, seccion_id) 
VALUES (:nombre, :apellido, :nie, :anio_id, :modalidad_id, :seccion_id)");

$stmt->execute([
    'nie' => $_POST['nie'],
    'nombre' => $_POST['nombre'],
    'apellido' => $_POST['apellido'],
    'anio_id' => $_POST['anio_id'],
    'modalidad_id' => $_POST['modalidad_id'],
    'seccion_id' => $_POST['seccion_id']
]);

echo "Alumno registrado correctamente.";
echo "<br><a href='../views/alumnos/crear.php'>Registrar otro</a>";
echo "<br><a href='../dashboard.php'>Ir al Dashboard</a>";

