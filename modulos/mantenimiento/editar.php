<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

redirectIfNotLoggedIn();

// Validar el parámetro ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de mantenimiento no válido.");
}

$id_mantenimiento = (int) $_GET['id'];

// Obtener datos actuales del mantenimiento
try {
    $stmt = $pdo->prepare("SELECT * FROM mantenimiento_preventivo WHERE id_mantenimiento = ?");
    $stmt->execute([$id_mantenimiento]);
    $mantenimiento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento) {
        die("Mantenimiento no encontrado.");
    }
} catch (PDOException $e) {
    die("Error al obtener el mantenimiento: " . $e->getMessage());
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_programada = $_POST['fecha_programada'];
    $descripcion = $_POST['descripcion'];
    $realizado = isset($_POST['realizado']) ? 1 : 0;
    $fecha_realizado = $_POST['fecha_realizado'] ?? null;

    try {
        $stmt = $pdo->prepare("UPDATE mantenimiento_preventivo SET fecha_programada = ?, descripcion = ?, realizado = ?, fecha_realizado = ? WHERE id_mantenimiento = ?");
        $stmt->execute([$fecha_programada, $descripcion, $realizado, $fecha_realizado, $id_mantenimiento]);
        header("Location: listado.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al actualizar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mantenimiento</title>
    <link rel="stylesheet" href="../../assets/css/formulario.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>

    <main>
        <h2>Editar Mantenimiento Preventivo</h2>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="fecha_programada">Fecha Programada:</label>
            <input type="date" name="fecha_programada" value="<?= htmlspecialchars($mantenimiento['fecha_programada']) ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required><?= htmlspecialchars($mantenimiento['descripcion']) ?></textarea>

            <label for="realizado">
                <input type="checkbox" name="realizado" <?= $mantenimiento['realizado'] ? 'checked' : '' ?>> Realizado
            </label>

            <label for="fecha_realizado">Fecha Realizado:</label>
            <input type="date" name="fecha_realizado" value="<?= htmlspecialchars($mantenimiento['fecha_realizado']) ?>">

            <button type="submit">Guardar Cambios</button>
            <a href="listado.php" class="btn-cancelar">Cancelar</a>
        </form>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>
