<?php include '../../includes/header.php'; ?>

<?php
$mensaje = '';
$tipo = ''; // success o error

if (isset($_GET['success'])) {
    $mensaje = htmlspecialchars($_GET['success']);
    $tipo = 'success';
} elseif (isset($_GET['error'])) {
    $mensaje = htmlspecialchars($_GET['error']);
    $tipo = 'error';
}
?>

<main>
    <section class="registrar-dispositivo">
        <h2>Registrar Nuevo Dispositivo</h2>
        
        <?php if ($mensaje): ?>
        <div id="popupMensaje" class="popup <?= $tipo ?>">
            <img src="../../assets/img/<?= $tipo ?>.png" alt="<?= $tipo ?>" width="50">
            <p><?= $mensaje ?></p>
            <button onclick="document.getElementById('popupMensaje').style.display='none'">Cerrar</button>
        </div>
        <?php endif; ?>

        <form action="guardar_dispositivo.php" method="POST" id="formDispositivo">
            <div class="form-group">
                <label for="nombre">Nombre del Dispositivo:</label>
                <input type="text" id="nombre" name="nombre" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo de Dispositivo:</label>
                
                <select id="tipo" name="tipo" required class="form-control" onchange="mostrarOtroCampo()">
    <option value="computadora">Computadora</option>
    <option value="impresora">Impresora</option>
    <option value="servidor">Servidor</option>
    <option value="switch">Switch</option>
    <option value="router">Router</option>
    <option value="laptop">Laptop</option>
    <option value="tablet">Tablet</option>
    <option value="proyector">Proyector</option>
    <option value="scanner">Scanner</option>
    <option value="monitor">Monitor</option>
    <option value="otro">Otro</option>
</select>




                
            </div>
            
            <div class="form-group">
                <label for="fecha">Fecha de Adquisición:</label>
                <input type="date" id="fecha" name="fecha" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Activo">Activo</option>
                    <option value="En Reparación">En Reparación</option>
                    <option value="Dado de Baja">Dado de Baja</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" required class="form-control">
            </div>
            




            <div class="form-group">
                <label for="n_serie">Número de Serie:</label>
                <input type="text" id="n_serie" name="n_serie" class="form-control">
            </div>

            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" class="form-control">
            </div>

            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" class="form-control">
            </div>

            <div class="form-group">
                <label for="licencia_software">Licencia de Software:</label>
                <input type="text" id="licencia_software" name="licencia_software" class="form-control">
            </div>

            <div class="form-group">
                <label for="fecha_venc_licencia">Fecha Vencimiento de Licencia:</label>
                <input type="date" id="fecha_venc_licencia" name="fecha_venc_licencia" class="form-control">
            </div>

            <div class="form-group">
                <label for="seguro_activo">¿Tiene Seguro Activo?</label>
                <select id="seguro_activo" name="seguro_activo" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_venc_seguro">Fecha Vencimiento de Seguro:</label>
                <input type="date" id="fecha_venc_seguro" name="fecha_venc_seguro" class="form-control">
            </div>

            <div class="form-group">
                <label for="responsable">Responsable:</label>
                <input type="text" id="responsable" name="responsable" class="form-control">
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
            </div>

            










            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="listado.php" class="btn btn-secondary">Ver Listado</a>
            </div>
        </form>
    </section>
</main>

<style>
.popup {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    border: 3px solid;
    padding: 20px;
    text-align: center;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    z-index: 1000;
    border-radius: 8px;
}
.popup.success {
    border-color: green;
}
.popup.error {
    border-color: red;
}
.popup img {
    display: block;
    margin: 0 auto 10px;
}
.popup button {
    margin-top: 10px;
    padding: 6px 12px;
    border: none;
    background-color: #333;
    color: white;
    cursor: pointer;
    border-radius: 4px;
}
</style>

<script>
// Validación adicional
document.getElementById('formDispositivo').addEventListener('submit', function(e) {
    const estado = document.getElementById('estado').value;
    const estadosValidos = ['Activo', 'En Reparación', 'Dado de Baja'];
    
    if (!estadosValidos.includes(estado)) {
        e.preventDefault();
        alert('Por favor seleccione un estado válido');
        return false;
    }
    return true;
});
</script>

<?php include '../../includes/footer.php'; ?>
