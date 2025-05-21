<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación y permisos
redirectIfNotLoggedIn();
requireAdmin();

// Inicializar variables
$errores = [];
$valores = [
    'nombre' => '',
    'correo' => '',
    'cargo'  => 'Usuario'
];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos
    $valores = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'correo' => trim($_POST['correo'] ?? ''),
        'cargo'  => $_POST['cargo'] ?? 'Usuario'
    ];
    $contrasena = $_POST['contrasena'] ?? '';

    // Validaciones
    if (empty($valores['nombre'])) {
        $errores[] = "El nombre es requerido";
    } elseif (strlen($valores['nombre']) > 100) {
        $errores[] = "El nombre no puede exceder 100 caracteres";
    }

    if (!filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    } elseif (strlen($valores['correo']) > 100) {
        $errores[] = "El correo no puede exceder 100 caracteres";
    }

    if (!isSecurePassword($contrasena)) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo";
    }

    // Si no hay errores, registrar usuario
    if (empty($errores)) {
        try {
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, correo, contrasena, cargo, creado_por) 
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $valores['nombre'],
                $valores['correo'],
                $hash,
                $valores['cargo'],
                $_SESSION['user_id']
            ]);

            $_SESSION['success'] = "Usuario registrado exitosamente";
            header("Location: listado.php");
            exit();

        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $errores[] = "El correo electrónico ya está registrado";
            } else {
                error_log("Error al registrar usuario: " . $e->getMessage());
                $errores[] = "Ocurrió un error al registrar el usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Usuario</title>
    <link rel="stylesheet" href="../../assets/css/usuarios.css">
</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h1>Registrar Nuevo Usuario</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert error">
            <?php foreach ($errores as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre"
                   value="<?= htmlspecialchars($valores['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo"
                   value="<?= htmlspecialchars($valores['correo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <small>Mínimo 8 caracteres, 1 mayúscula, 1 número y 1 símbolo</small>
        </div>

        <div class="form-group">
            <label for="cargo">Rol:</label>
            <select id="cargo" name="cargo" required>
                <option value="Usuario" <?= $valores['cargo'] === 'Usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="Técnico" <?= $valores['cargo'] === 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="Administrador" <?= $valores['cargo'] === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn">Registrar Usuario</button>
    </form>

    <a href="listado.php" class="btn-back">← Volver al listado</a>
</div>

<?php include '../../includes/footer.php'; ?>

</body>
</html>
