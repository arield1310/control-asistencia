<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $stmt = $pdo->prepare("UPDATE alumnos SET activo = false WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../views/alumnos/listado.php");
    exit;
}