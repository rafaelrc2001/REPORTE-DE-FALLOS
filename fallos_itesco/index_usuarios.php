<?php
// Incluir la conexión a la base de datos y el header
include 'includes/db.php';
include 'includes/header.php';
?>

<!-- Contenido de la página principal -->
<main>
    <section class="bienvenida">
        <h1>Bienvenido al Sistema de Gestión de Fallos de Equipos Electrónicos</h1>
        <h2>Cuenta De Usuario</h2>
        <p>Este sistema te permite gestionar dispositivos, reportar fallos, programar mantenimientos, registrar reparaciones y generar reportes.</p>
    </section>

    <section class="resumen">
        <h2>Resumen del Sistema</h2>
        <div class="resumen-cards">
            <!-- Tarjeta de Dispositivos -->
            <div class="card">
                <img src="assets/img/dispositivos.png" alt="Dispositivos" class="card-img">
                <h3>Dispositivos</h3>
                <p>Gestiona los dispositivos tecnológicos del ITESCO.</p>
                <a href="modulos/dispositivos/listado.php" class="btn">Ver Dispositivos</a>
            </div>

            <!-- Tarjeta de Fallos -->
            <div class="card">
                <img src="assets/img/falla.png" alt="Fallos" class="card-img">
                <h3>Fallos Reportados</h3>
                <p>Revisa y gestiona los fallos reportados en los dispositivos.</p>
                <a href="modulos/fallos/listado.php" class="btn">Ver Fallos</a>
            </div>

            
        </div>
    </section>

    <section class="acciones-rapidas">
        <h2>Acciones Rápidas</h2>
        <div class="acciones">
            <a href="modulos/fallos/reportar.php" class="btn-accion">Reportar un Fallo</a>
        </div>
    </section>
</main>

<?php
// Incluir el footer
include 'includes/footer.php';
?> 
