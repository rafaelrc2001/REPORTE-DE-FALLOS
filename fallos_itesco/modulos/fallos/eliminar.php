<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación
redirectIfNotLoggedIn();

// Verificar que hay un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de fallo inválido.");
}

$id = $_GET['id'];

try {
    // Eliminar el fallo con el ID proporcionado
    $stmt = $pdo->prepare("DELETE FROM fallos WHERE id_fallo = ?");
    $stmt->execute([$id]);

    // Redirigir al listado después de eliminar
    header("Location: listado.php");
    exit;
} catch (PDOException $e) {
    die("Error al eliminar el fallo: " . $e->getMessage());
}
