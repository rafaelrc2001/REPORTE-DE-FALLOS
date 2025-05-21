<?php
// Incluir la conexión a la base de datos
include '../../includes/db.php';

// Obtener el ID del fallo desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("ID de fallo no proporcionado.");
}

// Consultar la base de datos para obtener los detalles del fallo
$sql = "SELECT f.*, d.nombre AS dispositivo_nombre 
        FROM fallos f 
        JOIN dispositivos d ON f.dispositivo_id = d.id 
        WHERE f.id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $fallo = $result->fetch_assoc();
} else {
    die("Fallo no encontrado.");
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Detalles del Fallo</h2>
<table>
    <tr>
        <th>ID del Fallo</th>
        <td><?php echo $fallo['id']; ?></td>
    </tr>
    <tr>
        <th>Dispositivo</th>
        <td><?php echo $fallo['dispositivo_nombre']; ?></td>
    </tr>
    <tr>
        <th>Descripción del Fallo</th>
        <td><?php echo $fallo['descripcion']; ?></td>
    </tr>
    <tr>
        <th>Estado</th>
        <td><?php echo $fallo['estado']; ?></td>
    </tr>
    <tr>
        <th>Fecha de Reporte</th>
        <td><?php echo $fallo['fecha_reporte']; ?></td>
    </tr>
</table>

<a href="listado.php">Volver al Listado de Fallos</a>

<?php include '../../includes/footer.php'; ?> 
