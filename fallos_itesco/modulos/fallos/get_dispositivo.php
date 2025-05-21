<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación si es necesario
redirectIfNotLoggedIn();

if (isset($_GET['id_dispositivo'])) {
    $id = intval($_GET['id_dispositivo']);

    $stmt = $pdo->prepare("SELECT nombre, ubicacion, descripcion, estado FROM dispositivos WHERE id_dispositivo = ? AND estado = 'Activo'");
    $stmt->execute([$id]);
    $dispositivo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dispositivo) {
        header('Content-Type: application/json');
        echo json_encode($dispositivo);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Dispositivo no encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Falta parámetro id_dispositivo']);
}
