<?php
require_once "../config/database.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("
    UPDATE alumnos
    SET activo = FALSE
    WHERE id = :id
");

$stmt->execute(["id" => $id]);

header("Location: ../views/alumnos/listado.php");
exit;
