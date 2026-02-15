<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../dashboard.php");
    exit;
}

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$anio_id = $_POST["anio_id"];
$modalidad_id = $_POST["modalidad_id"];
$seccion_id = $_POST["seccion_id"];

$stmt = $pdo->prepare("
    INSERT INTO alumnos (nombre, apellido, anio_id, modalidad_id, seccion_id)
    VALUES (:nombre, :apellido, :anio_id, :modalidad_id, :seccion_id)
");

$stmt->execute([
    "nombre" => $nombre,
    "apellido" => $apellido,
    "anio_id" => $anio_id,
    "modalidad_id" => $modalidad_id,
    "seccion_id" => $seccion_id
]);

echo "Alumno registrado correctamente.";
echo "<br><a href='../views/alumnos/crear.php'>Registrar otro</a>";
echo "<br><a href='../dashboard.php'>Ir al Dashboard</a>";
