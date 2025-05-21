<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación y permisos
redirectIfNotLoggedIn();
requireAdmin();

// Validar ID
if (!isset($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = $_GET['id'];

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT nombre, correo, cargo FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $cargo = $_POST['cargo'] ?? '';

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($cargo)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Actualizar en BD
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ?, cargo = ? WHERE id_usuario = ?");
        $stmt->execute([$nombre, $correo, $cargo, $id]);
        header("Location: listado.php");
        exit;
    }
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h2>Editar Usuario</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

        <label for="cargo">Rol:</label>
        <select name="cargo" required>
            <option value="Administrador" <?= $usuario['cargo'] === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
            <option value="Técnico" <?= $usuario['cargo'] === 'Técnico' ? 'selected' : '' ?>>Técnico</option>
            <option value="Usuario" <?= $usuario['cargo'] === 'Usuario' ? 'selected' : '' ?>>Usuario</option>
        </select>

        <button type="submit">Guardar Cambios</button>
        <a href="listado.php" class="btn-back">← Cancelar</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
