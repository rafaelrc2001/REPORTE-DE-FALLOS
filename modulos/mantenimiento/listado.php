<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación y permisos
redirectIfNotLoggedIn();

try {
    // Consulta usando PDO con JOIN
    $stmt = $pdo->prepare("
        SELECT m.*, d.nombre AS dispositivo_nombre 
        FROM mantenimiento_preventivo m 
        JOIN dispositivos d ON m.id_dispositivo = d.id_dispositivo
    ");
    $stmt->execute();
    $mantenimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Error al obtener registros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Mantenimientos</title>
    <link rel="stylesheet" href="../../assets/css/mantenimiento.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>

    <main>
    <section class="listado-mantenimientos">
        <h2>Listado de Mantenimientos</h2>
        
        <?php if (empty($mantenimientos)): ?>
            <p class="no-registros">No hay mantenimientos programados</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dispositivo</th>
                        <th>Fecha Programada</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mantenimientos as $mantenimiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($mantenimiento['id_mantenimiento']) ?></td>
                        <td><?= htmlspecialchars($mantenimiento['dispositivo_nombre']) ?></td>
                        <td><?= htmlspecialchars($mantenimiento['fecha_programada']) ?></td>
                        <td><?= htmlspecialchars($mantenimiento['descripcion']) ?></td>
                        <td><?= $mantenimiento['realizado'] ? 'Completado' : 'Pendiente' ?></td>
                        <td class="acciones" style="border-bottom: none; display: flex; align-items: center; gap: 10px;">
    <a href="detalles.php?id=<?= $mantenimiento['id_mantenimiento'] ?>" title="Ver Detalles">
        <img src="/fallos_itesco/assets/img/detalles.png" alt="Ver Detalles" height="30" width="30">
    </a>
    <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
        <a href="editar.php?id=<?= $mantenimiento['id_mantenimiento'] ?>" title="Editar">
            <img src="/fallos_itesco/assets/img/editar.png" alt="Editar" height="30" width="30">
        </a>
        <a href="eliminar.php?id=<?= $mantenimiento['id_mantenimiento'] ?>" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este mantenimiento?');">
            <img src="/fallos_itesco/assets/img/eliminar.png" alt="Eliminar" height="30" width="30">
        </a>
    <?php endif; ?>
</td>


                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
            <a href="programar.php" class="btn-programar">Programar Nuevo Mantenimiento</a>
        <?php endif; ?>
    </section>
</main>


    <?php include '../../includes/footer.php'; ?>
</body>
</html>