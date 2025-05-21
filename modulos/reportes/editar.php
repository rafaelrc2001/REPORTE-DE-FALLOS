<?php

include '../../includes/db.php';  // Asegúrate de que la ruta sea correcta

// Verifica si la conexión fue exitosa
if (!$pdo) {
    die("Error en la conexión a la base de datos.");
}

// Verifica si se proporciona el ID
if (!isset($_GET['id'])) {
    die("ID de reporte no proporcionado.");
}

$id = $_GET['id'];

// Obtener los datos actuales del reporte
$stmt = $pdo->prepare("SELECT * FROM reportes WHERE id_reporte = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$reporte = $stmt->fetch();

if (!$reporte) {
    die("Reporte no encontrado.");
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $fecha_reporte = $_POST['fecha_reporte'];
    $descripcion = $_POST['descripcion'];

    if (empty($tipo) || empty($fecha_reporte) || empty($descripcion)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Actualizar los datos del reporte
        $updateStmt = $pdo->prepare("UPDATE reportes SET tipo = :tipo, fecha_reporte = :fecha_reporte, descripcion = :descripcion WHERE id_reporte = :id");
        $updateStmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $updateStmt->bindParam(':fecha_reporte', $fecha_reporte, PDO::PARAM_STR);
        $updateStmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            $success = "Reporte actualizado correctamente.";
            // Actualizar los datos del reporte en la variable $reporte
            $reporte['tipo'] = $tipo;
            $reporte['fecha_reporte'] = $fecha_reporte;
            $reporte['descripcion'] = $descripcion;
        } else {
            $error = "Error al actualizar el reporte.";
        }
    }
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Editar Reporte</h2>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if (isset($success)): ?>
    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form action="editar.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
    <label for="tipo">Tipo:</label>
    <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($reporte['tipo']); ?>" required>

    <label for="fecha_reporte">Fecha del reporte:</label>
    <input type="date" id="fecha_reporte" name="fecha_reporte" value="<?php echo htmlspecialchars($reporte['fecha_reporte']); ?>" required>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($reporte['descripcion']); ?></textarea>

    <button type="submit">Actualizar</button>
</form>

<a href="listado.php">Volver al Listado de Reportes</a>

<?php include '../../includes/footer.php'; ?>
