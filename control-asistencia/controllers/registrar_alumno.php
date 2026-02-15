<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $anio_id = $_POST["anio_id"];
    $modalidad_id = $_POST["modalidad_id"];
    $seccion_id = $_POST["seccion_id"];

    // 🔎 Verificar si ya existe
    $verificar = $pdo->prepare("
        SELECT id FROM alumnos
        WHERE nombre = :nombre
        AND apellido = :apellido
        AND anio_id = :anio
        AND modalidad_id = :modalidad
        AND seccion_id = :seccion
    ");

    $verificar->execute([
        "nombre" => $nombre,
        "apellido" => $apellido,
        "anio" => $anio_id,
        "modalidad" => $modalidad_id,
        "seccion" => $seccion_id
    ]);

    if ($verificar->rowCount() > 0) {

        $_SESSION["error"] = "⚠ El alumno ya está registrado en esta sección.";
        header("Location: ../views/alumnos/crear.php");
        exit;
    }

    // Insertar si no existe
    $insertar = $pdo->prepare("
        INSERT INTO alumnos (nombre, apellido, anio_id, modalidad_id, seccion_id)
        VALUES (:nombre, :apellido, :anio, :modalidad, :seccion)
    ");

    $insertar->execute([
        "nombre" => $nombre,
        "apellido" => $apellido,
        "anio" => $anio_id,
        "modalidad" => $modalidad_id,
        "seccion" => $seccion_id
    ]);

    $_SESSION["success"] = "✅ Alumno registrado correctamente.";
    header("Location: ../views/alumnos/crear.php");
    exit;
}
