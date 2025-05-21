<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listado.php");
    exit();
}

$id = $_GET['id'];

try {
    // Verificar si el dispositivo existe
    $stmt = $pdo->prepare("SELECT id_dispositivo FROM dispositivos WHERE id_dispositivo = ?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        header("Location: listado.php?error=dispositivo_no_encontrado");
        exit();
    }
    
    // Eliminar el dispositivo
    $stmt = $pdo->prepare("DELETE FROM dispositivos WHERE id_dispositivo = ?");
    $stmt->execute([$id]);
    
    header("Location: listado.php?success=dispositivo_eliminado");
    exit();
} catch (PDOException $e) {
    header("Location: listado.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>