<?php
// Incluir la conexión a la base de datos (asegúrate de que define $pdo)
include '../../includes/db.php';

// Verificar que la conexión esté definida
if (!isset($pdo)) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

// Obtener la lista de reportes
try {
    $sql = "SELECT * FROM reportes";
    $stmt = $pdo->query($sql);
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los reportes: " . $e->getMessage());
}
?>

<?php include '../../includes/header.php'; ?>

<main>
    <section class="listado-reportes">
        <h2>Listado de Reportes</h2>

        <!-- Tabla de reportes -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Fecha de Generación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportes as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_reporte']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_reporte']); ?></td>
                <td class="acciones" style="border-bottom: none; display: flex; align-items: center; gap: 10px;">
    <a href="detalles.php?id=<?= $row['id_reporte']; ?>">
        <img src="../../assets/img/detalles.png" alt="Ver Detalles" title="Ver Detalles" height="40">
    </a>
    <a href="editar.php?id=<?= $row['id_reporte']; ?>">
        <img src="../../assets/img/editar.png" alt="Editar" title="Editar" height="40">
    </a>
</td>


                </tr>
                <?php endforeach; ?>
            </tbody>


        </table>

        <!-- Botón para generar un nuevo reporte -->
        <a href="generar.php" class="btn-generar">Generar un Nuevo Reporte</a>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>
