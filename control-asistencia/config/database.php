<?php

$host = "localhost";
$port = "5433";
$dbname = "control_asistencia";
$user = "postgres";
$password = "PASSWORD_LOCAL";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
