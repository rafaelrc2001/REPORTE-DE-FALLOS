<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    die("ID de mantenimiento no especificado.");
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM mantenimiento_preventivo WHERE id_mantenimiento = ?");
    $stmt->execute([$id]);

    // Redirige al listado después de eliminar
    header("Location: listado.php");
    exit;
} catch (PDOException $e) {
    die("Error al eliminar mantenimiento: " . $e->getMessage());
}
