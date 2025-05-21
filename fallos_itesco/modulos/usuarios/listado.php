<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación
redirectIfNotLoggedIn();

try {
    $stmt = $pdo->query("SELECT id_usuario, nombre, cargo, correo, fecha_creacion FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener usuarios: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="../../assets/css/usuarios.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>

    <main>
        <section class="listado-usuarios">
            <h2>Listado de Usuarios</h2>

            <?php if (empty($usuarios)): ?>
                <p class="no-registros">No hay usuarios registrados.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Correo</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td>
                                <span class="badge 
                                    <?= $usuario['cargo'] === 'Administrador' ? 'bg-azul' :
                                        ($usuario['cargo'] === 'Técnico' ? 'bg-amarillo' : 'bg-gris'); ?>">
                                    <?= htmlspecialchars($usuario['cargo']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($usuario['correo']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?></td>
                            <td class="acciones">
    <a href="detalles.php?id=<?= $usuario['id_usuario'] ?>">
        <img src="../../assets/img/detalles.png" alt="Ver" title="Ver" height="40">
    </a>
    <?php if ($_SESSION['user_role'] === 'Administrador'): ?>
        <a href="editar.php?id=<?= $usuario['id_usuario'] ?>">
            <img src="../../assets/img/editar.png" alt="Editar" title="Editar" height="40">
        </a>
        <a href="eliminar.php?id=<?= $usuario['id_usuario'] ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
            <img src="../../assets/img/eliminar.png" alt="Eliminar" title="Eliminar" height="40">
        </a>
    <?php endif; ?>
</td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if ($_SESSION['user_role'] === 'Administrador'): ?>
                <a href="registrar.php">
    <img src="../../assets/img/anadir.png" alt="Añadir Usuario" title="Registrar Nuevo Usuario" height="50">
</a>

            <?php endif; ?>
        </section>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>
