<?php
require_once '../../includes/db.php';

// Obtener el ID del mantenimiento desde la URL
if (!isset($_GET['id'])) {
    die("ID de mantenimiento no proporcionado.");
}

$id = $_GET['id'];

// Consultar la base de datos usando PDO
$stmt = $pdo->prepare("
    SELECT m.*, d.nombre AS dispositivo_nombre 
    FROM mantenimiento_preventivo m 
    JOIN dispositivos d ON m.id_dispositivo = d.id_dispositivo 
    WHERE m.id_mantenimiento = ?
");
$stmt->execute([$id]);
$mantenimiento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mantenimiento) {
    die("Mantenimiento no encontrado.");
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Detalles del Mantenimiento</h2>
<table>
    <tr>
        <th>ID del Mantenimiento</th>
        <td><?php echo htmlspecialchars($mantenimiento['id_mantenimiento']); ?></td>
    </tr>
    <tr>
        <th>Dispositivo</th>
        <td><?php echo htmlspecialchars($mantenimiento['dispositivo_nombre']); ?></td>
    </tr>
    <tr>
        <th>Fecha Programada</th>
        <td><?php echo htmlspecialchars($mantenimiento['fecha_programada']); ?></td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td><?php echo htmlspecialchars($mantenimiento['descripcion']); ?></td>
    </tr>
    <tr>
        <th>Realizado</th>
        <td><?php echo htmlspecialchars($mantenimiento['realizado']); ?></td>
    </tr>
    <tr>
        <th>Fecha Realizado</th>
        <td><?php echo htmlspecialchars($mantenimiento['fecha_realizado']); ?></td>
    </tr>
</table>

<a href="listado.php">Volver al Listado de Mantenimientos</a>

<?php include '../../includes/footer.php'; ?>
