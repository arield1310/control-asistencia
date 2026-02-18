<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->execute(["username" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password == $user["password"]) {

        $_SESSION["usuario_id"] = $user["id"];
        $_SESSION["usuario"] = $user["nombre"];
        $_SESSION["rol"] = $user["rol"];

        // Redirigir al mismo dashboard para ambos roles
    header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Instituto Nacional de El Congo</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-header">
            <img src="/img/Logo INCO.png" class="login-logo">
            <h2>Instituto Nacional de El Congo</h2>
            <p>Control de Asistencia</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alerta-roja">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="floating-group">
                <input type="text" name="username" placeholder=" " required>
                <label><i class="bi bi-person"></i> Usuario</label>
            </div>

            <div class="floating-group">
                <input type="password" id="password" name="password" placeholder=" " required>
                <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                <label><i class="bi bi-key"></i> Contraseña</label>
            </div>

            <button type="submit" class="btn-login">
                Ingresar
            </button>

        </form>

    </div>

</div>

<script>
const toggle = document.getElementById("togglePassword");
const password = document.getElementById("password");

toggle.addEventListener("click", function() {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
});
</script>
</body>
</html>








