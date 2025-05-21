<?php
ob_start();
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Verificar autenticación
redirectIfNotLoggedIn();

// Solo Técnicos o Administradores pueden editar
if ($_SESSION['user_role'] !== 'Administrador' && $_SESSION['user_role'] !== 'Técnico') {
    die('Acceso denegado');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID de fallo no proporcionado');
}

// Obtener datos del fallo
$stmt = $pdo->prepare("SELECT * FROM fallos WHERE id_fallo = ?");
$stmt->execute([$id]);
$fallo = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$fallo) {
    die('Fallo no encontrado');
}

// Actualizar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $estado_fallo = $_POST['estado_fallo'];
    $fecha_resuelto = $_POST['fecha_resuelto'] ?? null;

    $stmt = $pdo->prepare("UPDATE fallos SET descripcion = ?, estado_fallo = ?, fecha_resuelto = ? WHERE id_fallo = ?");
    $stmt->execute([$descripcion, $estado_fallo, $fecha_resuelto ?: null, $id]);

    header("Location: ../../modulos/fallos/listado.php");  
    exit;
}
?>

<main>
    <h2>Editar Fallo</h2>
    <form method="POST">
        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?= htmlspecialchars($fallo['descripcion']) ?></textarea><br><br>

        <label>Estado:</label><br>
        <select name="estado_fallo">
            <option value="Pendiente" <?= $fallo['estado_fallo'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="En Proceso" <?= $fallo['estado_fallo'] === 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="Resuelto" <?= $fallo['estado_fallo'] === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
        </select><br><br>

        <label>Fecha de Resolución (opcional):</label><br>
        <input type="datetime-local" name="fecha_resuelto" value="<?= $fallo['fecha_resuelto'] ? date('Y-m-d\TH:i', strtotime($fallo['fecha_resuelto'])) : '' ?>"><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</main>

<?php require_once '../../includes/footer.php'; ?>
