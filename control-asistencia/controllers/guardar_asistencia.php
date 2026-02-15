<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../dashboard.php");
    exit;
}

$fecha = $_POST["fecha"];
$estados = $_POST["estado"];

foreach ($estados as $alumno_id => $estado) {
    $stmt = $pdo->prepare("
        INSERT INTO asistencia (alumno_id, fecha, estado)
        VALUES (:alumno_id, :fecha, :estado)
    ");

    $stmt->execute([
        "alumno_id" => $alumno_id,
        "fecha" => $fecha,
        "estado" => $estado
    ]);
}

echo "Asistencia guardada correctamente.";
echo "<br><a href='../dashboard.php'>Volver</a>";
