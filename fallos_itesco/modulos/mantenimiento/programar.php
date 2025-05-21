<?php

ob_start();

require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Verificar autenticación
redirectIfNotLoggedIn();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dispositivo = $_POST['id_dispositivo'];
    $fecha_programada = $_POST['fecha_programada'];
    $descripcion = $_POST['descripcion'];
    $realizado = isset($_POST['realizado']) ? 1 : 0;
    $fecha_realizado = $realizado ? date('Y-m-d') : null;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO mantenimiento_preventivo (id_dispositivo, fecha_programada, descripcion, realizado, fecha_realizado)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$id_dispositivo, $fecha_programada, $descripcion, $realizado, $fecha_realizado]);
        header('Location: ../../modulos/mantenimiento/listado.php');
        exit;

    } catch (PDOException $e) {
        echo "<p class='error'>Error al registrar mantenimiento: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Obtener dispositivos
$dispositivos = [];
try {
    $stmt = $pdo->query("SELECT id_dispositivo, nombre FROM dispositivos ORDER BY nombre ASC");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener dispositivos: " . $e->getMessage());
}
?>

<main>
    <section class="programar-mantenimiento">
        <h2>Programar Mantenimiento Preventivo</h2>

        <form method="POST" action="programar.php">
            <label for="id_dispositivo">Dispositivo:</label>
            <select name="id_dispositivo" id="id_dispositivo" required>
                <option value="">Seleccione un dispositivo</option>
                <?php foreach ($dispositivos as $dispositivo): ?>
                    <option value="<?= $dispositivo['id_dispositivo'] ?>">
                        <?= htmlspecialchars($dispositivo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="fecha_programada">Fecha Programada:</label>
            <input type="date" name="fecha_programada" id="fecha_programada" required>

            <label for="descripcion">Descripción del Mantenimiento:</label>
            <textarea name="descripcion" id="descripcion" rows="4" required></textarea>

            <label>
                <input type="checkbox" name="realizado" value="1">
                ¿Ya se realizó el mantenimiento?
            </label>

            <button type="submit" class="btn">Guardar Mantenimiento</button>
        </form>
    </section>
</main>

<?php require_once '../../includes/footer.php'; ?>
