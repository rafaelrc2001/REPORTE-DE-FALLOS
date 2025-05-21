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

// Proteger: no permitir que un admin se elimine a sí mismo
if ($id == $_SESSION['user_id']) {
    die("No puedes eliminar tu propia cuenta.");
}

// Eliminar usuario
$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);

// Redirigir al listado
header("Location: listado.php");
exit;
?>
