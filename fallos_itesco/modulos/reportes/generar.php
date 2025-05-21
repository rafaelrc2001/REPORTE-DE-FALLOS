<?php
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $fecha_reporte = $_POST['fecha_reporte'];
    $descripcion = $_POST['descripcion'];

    // Usar solo los campos que existen en la tabla reportes
    $sql = "INSERT INTO reportes (tipo, fecha_reporte, descripcion) 
            VALUES (:tipo, :fecha_reporte, :descripcion)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_reporte', $fecha_reporte, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Reporte generado exitosamente.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<?php include '../../includes/header.php'; ?>

<main>
    <section class="generar-reporte">
        <h2>Generar Reporte</h2>
        <form method="POST" action="">
            <label for="tipo">Tipo de Reporte:</label>
            <select id="tipo" name="tipo" required>
                <option value="Fallo">Fallo</option>
                <option value="Mantenimiento">Mantenimiento</option>
                <option value="Inventario">Inventario</option>
            </select>

            <label for="fecha_reporte">Fecha del Reporte:</label>
            <input type="date" id="fecha_reporte" name="fecha_reporte" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <button type="submit">Generar Reporte</button>
        </form>

        <a href="listado.php">Volver al Listado de Reportes</a>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>
