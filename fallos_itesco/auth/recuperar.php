<?php require_once '../includes/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <h1>Recuperar Contraseña</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'];
            
            // Aquí deberías implementar lógica para enviar un correo con un enlace de recuperación
            echo "<p class='success'>Se ha enviado un enlace de recuperación a tu correo.</p>";
        }
        ?>
        <form method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <button type="submit">Enviar enlace</button>
        </form>
        <p><a href="login.php">Volver al inicio de sesión</a></p>
    </div>
</body>
</html>
