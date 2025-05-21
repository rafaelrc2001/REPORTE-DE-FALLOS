<?php 
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header("Location: /fallos_itesco/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - TecNM</title>
  <link rel="stylesheet" href="../assets/css/auth.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: rgb(142, 142, 196);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background: url('/fallos_itesco/assets/img/itesco2.png') no-repeat center center fixed;
      background-size: 80%;
      background-position: center top;
    }

    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
    }

    .login-box {
      display: flex;
      width: 75%;
      max-width: 1100px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .login-left {
      width: 50%;
      background-color: white;
      padding: 30px;
      text-align: center;
      color: #333;
      border-right: 2px solid #ddd;
    }

    .login-left h2 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .login-right {
      width: 50%;
      background-color: white;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      border-left: 2px solid #ddd;
      border: 2px solid blue;
      border-radius: 10px;
    }

    .login-right h3 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .form-group {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      border: 2px solid #ddd;
      padding: 10px;
      border-radius: 5px;
    }

    .form-group input {
      border: none;
      outline: none;
      padding: 10px;
      font-size: 16px;
      width: 100%;
    }

    .form-group .icon {
      font-size: 20px;
      margin-right: 10px;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px;
      font-size: 18px;
      width: 100%;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="login-wrapper">
    <div class="login-box">
      
      <!-- Sección izquierda -->
      <div class="login-left">
        <h2>SISTEMA DE REPORTES DE FALLOS</h2>
      </div>

      <!-- Sección derecha (formulario) -->
      <div class="login-right">
        <h3>¿Ya tiene una cuenta?</h3>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';

            if (loginUser($correo, $contrasena)) {
                $rol = $_SESSION['user_role'] ?? '';
                header("Location: /fallos_itesco/" . ($rol === 'usuario' ? "index_usuarios.php" : "index.php"));
                exit();
            } else {
                echo "<p class='error'>Credenciales incorrectas</p>";
            }
        }
        ?>

        <form method="POST">
          <div class="form-group">
            <span class="icon">👤</span>
            <input type="email" name="correo" placeholder="Correo" required>
          </div>

          <div class="form-group">
            <span class="icon">🔒</span>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
          </div>

          <button type="submit">Iniciar sesión</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
