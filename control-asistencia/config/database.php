<?php

$host = $_ENV['DB_HOST'] ?? "localhost";
$port = $_ENV['DB_PORT'] ?? "5433";
$dbname = $_ENV['DB_NAME'] ?? "control_asistencia";
$user = $_ENV['DB_USER'] ?? "postgres";
$password = $_ENV['DB_PASS'] ?? "";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
