<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SESSION['user_role'] === 'Técnico') {
    $stmtFallo = $pdo->query("SELECT COUNT(*) FROM fallos WHERE estado_fallo != 'Resuelto'");
    $fallosPendientes = max(0, $stmtFallo->fetchColumn()); // Restamos 2 pero no bajamos de 0

    $stmtMant = $pdo->query("SELECT COUNT(*) FROM mantenimiento_preventivo WHERE realizado = 0");
    $mantenimientosPendientes = $stmtMant->fetchColumn();
}
?>

<!-- Alerta con las cantidades separadas -->
<?php if (isset($fallosPendientes) && isset($mantenimientosPendientes) && ($fallosPendientes > 0 || $mantenimientosPendientes > 0)): ?>
    <div style="background-color: red; color: white; padding: 10px; text-align: center;">
        ⚠️ Tienes <?= $fallosPendientes ?> fallo(s) pendiente(s) y <?= $mantenimientosPendientes ?> mantenimiento(s) pendiente(s).
        <a href="/fallos_itesco/modulos/fallos/listado.php" style="color: yellow; font-weight: bold;">Ver detalles</a>
    </div>
<?php endif; ?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Fallos - ITESCO</title>
    <link rel="stylesheet" href="/fallos_itesco/assets/css/styles.css">
    <link rel="stylesheet" href="/fallos_itesco/assets/css/dispositivos.css">
    <link rel="stylesheet" href="/fallos_itesco/assets/css/reportes.css">
    <link rel="stylesheet" href="/fallos_itesco/assets/css/mantenimiento.css">
    <link rel="stylesheet" href="/fallos_itesco/assets/css/fallos.css">


 <!-- Aquí empieza tu CSS personalizado -->
    <style>
    .user-role {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    background-color:rgb(255, 255, 255);
    padding: 5px 10px; /* Ajusta el padding para que se haga más pequeño */
    border-radius: 8px;
    font-weight: bold;
    box-shadow: 0 0 5px rgba(255, 255, 255, 0.2);
    font-size: 1.2em; /* Puede seguir siendo más pequeño */
}

.user-role span {
    font-size: 0.8em; /* Prueba con un tamaño aún más pequeño */
}

.user-role img.role-icon {
    width: 60px; /* Ajusta el tamaño de la imagen */
    height: 30px; /* Mantén la altura proporcional */
    max-width: 100%; /* Evita que la imagen se estire demasiado */
    max-height: 100%; /* Mantiene la imagen dentro del contenedor sin deformarse */
    margin-right: 8px; /* Espacio reducido entre el icono y el texto */
}



    nav ul li a {
        font-size: 1.2em;
    }

    nav ul li img {
        width: 40px;
        height: auto;
        margin-right: 15px;
        vertical-align: middle;
    }

    .user-role span {
    font-size: 0.9em; /* o incluso 0.8em si lo quieres más pequeño */
}

.user-role {
    position: absolute;
    top: 75px;
    right: 50px;
    display: flex;
    align-items: center;
    background-color:rgb(0, 0, 0);
    padding: 5px 7px;
    border-radius: 4px;
    font-weight: bold;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    font-size: .8em;
}

.user-role span {
    font-size: 0.9em;
}

.user-role img.role-icon {
    width: 30px; /* Ajusta el tamaño de la imagen */
    height: 30px; /* Mantén la altura proporcional */
    max-width: 100%; /* Evita que la imagen se estire demasiado */
    max-height: 100%; /* Mantiene la imagen dentro del contenedor sin deformarse */
    margin-right: 8px; /* Espacio reducido entre el icono y el texto */
}



</style>

    

</li>

</head>






<body>
    <header>
        <div class="header-container">
            <!-- Logo de la escuela -->
            <div class="logo">
                <img src="/fallos_itesco/assets/img/logo1.jpg" alt="Logo ITESCO">
            </div>
            <!-- Título y barra de navegación -->
            <div class="header-content">
                <h1>Gestión de Fallos de Equipos Tecnológicos</h1>
                <nav>
    <ul>
        <li>
            <a href="/fallos_itesco/index.php">
                <img src="/fallos_itesco/assets/img/inicio.png" alt="Inicio">
                Inicio |
            </a>
        </li>
        <li>
            <a href="/fallos_itesco/modulos/dispositivos/listado.php">
                <img src="/fallos_itesco/assets/img/dispositivos1.png" alt="Dispositivos" >
                Dispositivos |
            </a>
        </li>
        <li>
            <a href="/fallos_itesco/modulos/fallos/listado.php">
                <img src="/fallos_itesco/assets/img/fallos.png" alt="Fallos" >
                Fallos |
            </a>
        </li>

        <?php if (isset($_SESSION['user_role'])): ?>
            <?php if ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Técnico'): ?>
                <li>
                    <a href="/fallos_itesco/modulos/mantenimiento/listado.php">
                        <img src="/fallos_itesco/assets/img/mantenimiento1.png" alt="Mantenimiento" >
                        Mantenimiento |
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_role'] === 'Administrador'): ?>
                <li>
                    <a href="/fallos_itesco/modulos/usuarios/listado.php">
                        <img src="/fallos_itesco/assets/img/usuarios.png" alt="Usuarios" >
                        Usuarios |
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="/fallos_itesco/auth/logout.php">
                    <img src="/fallos_itesco/assets/img/salir.png" alt="Salir" >
                    Salir |
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>




<?php if (isset($totalAlertas) && $totalAlertas > 0): ?>
    <div style="background-color: red; color: white; padding: 10px; text-align: center;">
        ⚠️ Tienes <?= $totalAlertas ?> alerta(s) pendiente(s) por atender. 
        <a href="/fallos_itesco/modulos/fallos/listado.php" style="color: yellow; font-weight: bold;">Ver detalles</a>
    </div>
<?php endif; ?>




<?php if (isset($_SESSION['user_role'])): ?>
    <div class="user-role">
        <?php if ($_SESSION['user_role'] === 'Administrador'): ?>
            <img src="/fallos_itesco/assets/img/usuarios.png" alt="Administrador" class="role-icon">
            <span>Administrador</span>
        <?php elseif ($_SESSION['user_role'] === 'Técnico'): ?>
            <img src="/fallos_itesco/assets/img/usuario.png" alt="Técnico" class="role-icon">
            <span>Técnico</span>
        <?php elseif ($_SESSION['user_role'] === 'Usuario'): ?>
            <img src="/fallos_itesco/assets/img/usuario.png" alt="Usuario" class="role-icon">
            <span>Usuario</span>
        <?php endif; ?>
    </div>
<?php endif; ?>


            </div>
        </div>
    </header>




<?php


?>