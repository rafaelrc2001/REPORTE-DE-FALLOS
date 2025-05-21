<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación y permisos
redirectIfNotLoggedIn();
requireAdmin();

// Validar ID de usuario
if (!isset($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = $_GET['id'];

// Consultar usuario
$stmt = $pdo->prepare("
    SELECT id_usuario, nombre, correo, cargo, fecha_creacion 
    FROM usuarios 
    WHERE id_usuario = ?
");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h2>Detalles del Usuario</h2>
    <table>
        <tr>
            <th>ID del Usuario</th>
            <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
        </tr>
        <tr>
            <th>Nombre</th>
            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
        </tr>
        <tr>
            <th>Correo Electrónico</th>
            <td><?= htmlspecialchars($usuario['correo']) ?></td>
        </tr>
        <tr>
            <th>Rol</th>
            <td><?= htmlspecialchars($usuario['cargo']) ?></td>
        </tr>
        <tr>
            <th>Fecha de Creación</th>
            <td><?= htmlspecialchars($usuario['fecha_creacion']) ?></td>
        </tr>
    </table>

    <a href="listado.php" class="btn-back">← Volver al Listado de Usuarios</a>
</div>

<?php include '../../includes/footer.php'; ?>

