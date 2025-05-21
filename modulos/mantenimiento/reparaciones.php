<?php
// Incluir la conexión a la base de datos
include '../../includes/db.php';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fallo_id = $_POST['fallo_id'];
    $tecnico_id = $_POST['tecnico_id'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];

    // Insertar la reparación en la base de datos
    $sql = "INSERT INTO reparaciones (fallo_id, tecnico_id, descripcion, costo, fecha_reparacion) 
            VALUES ('$fallo_id', '$tecnico_id', '$descripcion', '$costo', NOW())";

    if ($conn->query($sql)) {
        echo "Reparación registrada exitosamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Registrar Reparación</h2>
<form method="POST" action="">
    <label for="fallo_id">Fallo:</label>
    <select id="fallo_id" name="fallo_id" required>
        <?php
        // Obtener la lista de fallos pendientes
        $sql = "SELECT id, descripcion FROM fallos WHERE estado = 'Pendiente'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['descripcion']}</option>";
        }
        ?>
    </select>

    <label for="tecnico_id">Técnico Responsable:</label>
    <select id="tecnico_id" name="tecnico_id" required>
        <?php
        // Obtener la lista de técnicos
        $sql = "SELECT id, nombre FROM usuarios WHERE cargo = 'Técnico'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
        }
        ?>
    </select>

    <label for="descripcion">Descripción de la Reparación:</label>
    <textarea id="descripcion" name="descripcion" required></textarea>

    <label for="costo">Costo de la Reparación:</label>
    <input type="number" id="costo" name="costo" step="0.01" required>

    <button type="submit">Registrar Reparación</button>
</form>

<a href="listado.php">Volver al Listado de Reparaciones</a>

<?php include '../../includes/footer.php'; ?> 
