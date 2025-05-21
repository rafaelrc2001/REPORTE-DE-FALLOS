<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

redirectIfNotLoggedIn();

try {
    $stmt = $pdo->query("SELECT * FROM dispositivos ORDER BY id_dispositivo");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener dispositivos: " . $e->getMessage());
}
?>

<main>
    <section class="listado-fallos">
        <h2>Listado de Dispositivos</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="alert alert-success">Dispositivo registrado correctamente</div>
        <?php endif; ?>

        <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
            <a href="registrar.php" class="btn-reportar">
                <img src="/fallos_itesco/assets/img/anadir.png" alt="Agregar" title="Agregar dispositivo" style="height: 40px;">
            </a>
        <?php endif; ?>

        <?php if (empty($dispositivos)): ?>
            <p class="no-fallos">No hay dispositivos registrados</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fecha de Adquisición</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th>Número de Serie</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Licencia de Software</th>
                        <th>Vencimiento de Licencia</th>
                        <th>Seguro Activo</th>
                        <th>Vencimiento de Seguro</th>
                        <th>Responsable</th>
                        <th>Observaciones</th>
                        <th>Fecha de Registro</th>
                        <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dispositivos as $dispositivo): ?>
                    <tr>
                        <td><?= htmlspecialchars($dispositivo['id_dispositivo']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['nombre']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['tipo']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['fecha_adquisicion']) ?></td>
                        <td class="estado-<?= strtolower(str_replace(' ', '-', str_replace('_', ' ', $dispositivo['estado']))) ?>">
                            <?= ucwords(str_replace('_', ' ', htmlspecialchars($dispositivo['estado']))) ?>
                        </td>
                        <td><?= htmlspecialchars($dispositivo['ubicacion']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['n_serie']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['marca']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['modelo']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['licencia_software']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['fecha_venc_licencia']) ?></td>
                        <td><?= $dispositivo['seguro_activo'] ? 'Sí' : 'No' ?></td>
                        <td><?= htmlspecialchars($dispositivo['fecha_venc_seguro']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['responsable']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['observaciones']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['fecha_registro']) ?></td>
                        <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
                            <td class="acciones" style="border-bottom: none; display: flex; align-items: center; gap: 10px;">
                                <a href="editar.php?id=<?= $dispositivo['id_dispositivo'] ?>" title="Editar">
                                    <img src="/fallos_itesco/assets/img/editar.png" alt="Editar" height="30" width="30">
                                </a>
                                <a href="eliminar.php?id=<?= $dispositivo['id_dispositivo'] ?>" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este dispositivo?')">
                                    <img src="/fallos_itesco/assets/img/eliminar.png" alt="Eliminar" height="30" width="30">
                                </a>
                            </td>
                        <?php endif; ?>
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
