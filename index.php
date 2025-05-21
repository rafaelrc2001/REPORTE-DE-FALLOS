<?php
// Incluir la conexión a la base de datos y el header
include 'includes/db.php';
include 'includes/header.php';
?>

<!-- Contenido de la página principal -->
<main>
    <section class="bienvenida">
        <h1>Bienvenido al Sistema de Gestión de Fallos de Equipos Electrónicos</h1>
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

            <!-- Tarjeta de Mantenimiento -->
            <div class="card">
                <img src="assets/img/mantenimiento.png" alt="Mantenimiento" class="card-img">
                <h3>Mantenimientos</h3>
                <p>Programa y revisa los mantenimientos preventivos.</p>
                <a href="modulos/mantenimiento/listado.php" class="btn">Ver Mantenimientos</a>
            </div>

            <!-- Tarjeta de Reportes -->
            <div class="card">
                <img src="assets/img/reporte.png" alt="Reportes" class="card-img">
                <h3>Reportes</h3>
                <p>Genera y descarga reportes de fallos, mantenimientos y más.</p>
                <a href="modulos/reportes/listado.php" class="btn">Ver Reportes</a>
            </div>
        </div>
    </section>

  
</main>

<?php
// Incluir el footer
include 'includes/footer.php';
?> 
