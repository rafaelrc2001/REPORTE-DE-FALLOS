<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Verificar autenticación
redirectIfNotLoggedIn();

try {
    // Consulta usando PDO con JOIN
    $stmt = $pdo->prepare("
        SELECT f.*, d.nombre AS dispositivo_nombre 
        FROM fallos f 
        JOIN dispositivos d ON f.id_dispositivo = d.id_dispositivo
        ORDER BY f.fecha_reportado DESC
    ");
    $stmt->execute();
    $fallos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener fallos: " . $e->getMessage());
}
?>

<main>
    <section class="listado-fallos">
        <h2>Listado de Fallos</h2>

        <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico' || $_SESSION['user_role'] === 'Usuario'): ?>
            <a href="reportar.php" title="Reportar un Nuevo Fallo">
                <img src="/fallos_itesco/assets/img/anadir.png" alt="Añadir Fallo" style="height: 50px;">
            </a>
        <?php endif; ?>


        <?php if (empty($fallos)): ?>
            <p class="no-fallos">No hay fallos registrados</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dispositivo</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha Reporte</th>
                        <th>Fecha Resolución</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fallos as $fallo): ?>
                    <tr>
                        <td><?= htmlspecialchars($fallo['id_fallo']) ?></td>
                        <td><?= htmlspecialchars($fallo['dispositivo_nombre']) ?></td>
                        <td><?= htmlspecialchars($fallo['descripcion']) ?></td>
                        <td class="estado-<?= strtolower(str_replace(' ', '-', $fallo['estado_fallo'])) ?>">
                            <?= htmlspecialchars($fallo['estado_fallo']) ?>
                        </td>
                        <td><?= htmlspecialchars($fallo['fecha_reportado']) ?></td>
                        <td><?= $fallo['fecha_resuelto'] ? htmlspecialchars($fallo['fecha_resuelto']) : 'Pendiente' ?></td>
                      <td class="acciones" style="border-bottom: none; display: flex; align-items: center; gap: 10px;">
    <a href="detalles.php?id=<?= $fallo['id_fallo'] ?>" title="Ver Detalles">
        <img src="/fallos_itesco/assets/img/detalles.png" alt="Ver Detalles" style="height: 32px;">
    </a>
    <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
        <a href="editar.php?id=<?= $fallo['id_fallo'] ?>" title="Editar">
            <img src="/fallos_itesco/assets/img/editar.png" alt="Editar" style="height: 32px;">
        </a>
        <a href="eliminar.php?id=<?= $fallo['id_fallo'] ?>" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este fallo?');">
            <img src="/fallos_itesco/assets/img/eliminar.png" alt="Eliminar" style="height: 32px;">
        </a>
    <?php endif; ?>
</td>



                            
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php
require_once '../../includes/footer.php';
?>