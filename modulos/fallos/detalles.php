<?php
require_once '../../includes/db.php';

// Obtener el ID del fallo desde la URL
if (!isset($_GET['id'])) {
    die("ID de fallo no proporcionado.");
}

$id = $_GET['id'];

// Consultar usando PDO
$stmt = $pdo->prepare("
    SELECT f.*, d.nombre AS dispositivo_nombre 
    FROM fallos f 
    JOIN dispositivos d ON f.id_dispositivo = d.id_dispositivo 
    WHERE f.id_fallo = ?
");
$stmt->execute([$id]);
$fallo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fallo) {
    die("Fallo no encontrado.");
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Detalles del Fallo</h2>
<table>
    <tr>
        <th>ID del Fallo</th>
        <td><?php echo htmlspecialchars($fallo['id_fallo']); ?></td>
    </tr>
    <tr>
        <th>Dispositivo</th>
        <td><?php echo htmlspecialchars($fallo['dispositivo_nombre']); ?></td>
    </tr>
    <tr>
        <th>Descripción del Fallo</th>
        <td><?php echo htmlspecialchars($fallo['descripcion']); ?></td>
    </tr>
    <tr>
        <th>Estado</th>
        <td><?php echo htmlspecialchars($fallo['estado_fallo']); ?></td>
    </tr>
    <tr>
        <th>Fecha de Reporte</th>
        <td><?php echo htmlspecialchars($fallo['fecha_reportado']); ?></td>
    </tr>
</table>

<a href="listado.php">Volver al Listado de Fallos</a>

<?php include '../../includes/footer.php'; ?>
