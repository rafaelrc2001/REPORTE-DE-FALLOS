<?php

include '../../includes/db.php';  // Asegúrate de que esta ruta sea correcta

// Verifica si la conexión fue exitosa
if (!$pdo) {
    die("Error en la conexión a la base de datos.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("ID de reporte no proporcionado.");
}

// Usamos una consulta preparada para evitar inyecciones SQL
$stmt = $pdo->prepare("SELECT * FROM reportes WHERE id_reporte = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);  // Bind del parámetro
$stmt->execute();
$reporte = $stmt->fetch();

if (!$reporte) {
    die("Reporte no encontrado.");
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Detalles del Reporte</h2>
<table>
    <tr>
        <th>ID del Reporte</th>
        <td><?php echo htmlspecialchars($reporte['id_reporte']); ?></td>
    </tr>
    <tr>
        <th>Tipo de Reporte</th>
        <td><?php echo htmlspecialchars($reporte['tipo']); ?></td>
    </tr>
    <tr>
        <th>Rango de Fechas</th>
        <td><?php echo htmlspecialchars($reporte['fecha_inicio'] ?? 'No disponible') . " - " . htmlspecialchars($reporte['fecha_fin'] ?? 'No disponible'); ?></td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td><?php echo htmlspecialchars($reporte['descripcion']); ?></td>
    </tr>
</table>

<a href="listado.php">Volver al Listado de Reportes</a>

<?php include '../../includes/footer.php'; ?>
