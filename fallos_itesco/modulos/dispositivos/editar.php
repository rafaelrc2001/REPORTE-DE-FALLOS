<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: listado.php");
    exit();
}

$id = $_GET['id'];

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$fecha = $_POST['fecha'];
$estado = $_POST['estado'];
$ubicacion = $_POST['ubicacion'];
$n_serie = $_POST['n_serie'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$licencia_software = $_POST['licencia_software'];
$fecha_venc_licencia = $_POST['fecha_venc_licencia'];
$seguro_activo = $_POST['seguro_activo'];
$fecha_venc_seguro = $_POST['fecha_venc_seguro'];
$responsable = $_POST['responsable'];
$observaciones = $_POST['observaciones'];

$sql = "UPDATE dispositivos SET 
    nombre = :nombre,
    tipo = :tipo,
    fecha_adquisicion = :fecha,
    estado = :estado,
    ubicacion = :ubicacion,
    n_serie = :n_serie,
    marca = :marca,
    modelo = :modelo,
    licencia_software = :licencia_software,
    fecha_venc_licencia = :fecha_venc_licencia,
    seguro_activo = :seguro_activo,
    fecha_venc_seguro = :fecha_venc_seguro,
    responsable = :responsable,
    observaciones = :observaciones
    WHERE id_dispositivo = :id";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':fecha', $fecha);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':ubicacion', $ubicacion);
$stmt->bindParam(':n_serie', $n_serie);
$stmt->bindParam(':marca', $marca);
$stmt->bindParam(':modelo', $modelo);
$stmt->bindParam(':licencia_software', $licencia_software);
$stmt->bindParam(':fecha_venc_licencia', $fecha_venc_licencia);
$stmt->bindParam(':seguro_activo', $seguro_activo);
$stmt->bindParam(':fecha_venc_seguro', $fecha_venc_seguro);
$stmt->bindParam(':responsable', $responsable);
$stmt->bindParam(':observaciones', $observaciones);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: listado.php?success=dispositivo_actualizado");
            exit();
        }
    } catch (PDOException $e) {
        die("Error al actualizar dispositivo: " . $e->getMessage());
    }
}

// Obtener datos del dispositivo solo si no se envió el formulario
try {
    $stmt = $pdo->prepare("SELECT * FROM dispositivos WHERE id_dispositivo = ?");
    $stmt->execute([$id]);
    $dispositivo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dispositivo) {
        header("Location: listado.php?error=dispositivo_no_encontrado");
        exit();
    }
} catch (PDOException $e) {
    die("Error al obtener dispositivo: " . $e->getMessage());
}
?>

<?php require_once '../../includes/header.php'; ?>

<main>
    <section class="editar-dispositivo">
        <h2>Editar Dispositivo</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Dispositivo:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($dispositivo['nombre']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo de Dispositivo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="computadora" <?= $dispositivo['tipo'] == 'computadora' ? 'selected' : '' ?>>Computadora</option>
                    <option value="impresora" <?= $dispositivo['tipo'] == 'impresora' ? 'selected' : '' ?>>Impresora</option>
                    <option value="servidor" <?= $dispositivo['tipo'] == 'servidor' ? 'selected' : '' ?>>Servidor</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="fecha">Fecha de Adquisición:</label>
                <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($dispositivo['fecha_adquisicion']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Activo" <?= $dispositivo['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="En Reparación" <?= $dispositivo['estado'] == 'En Reparación' ? 'selected' : '' ?>>En Reparación</option>
                    <option value="Dado de Baja" <?= $dispositivo['estado'] == 'Dado de Baja' ? 'selected' : '' ?>>Dado de Baja</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" value="<?= htmlspecialchars($dispositivo['ubicacion']) ?>" required>
            </div>
            
            <div class="form-group">
    <label for="n_serie">Número de Serie:</label>
    <input type="text" id="n_serie" name="n_serie" value="<?= htmlspecialchars($dispositivo['n_serie']) ?>">
</div>

<div class="form-group">
    <label for="marca">Marca:</label>
    <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($dispositivo['marca']) ?>">
</div>

<div class="form-group">
    <label for="modelo">Modelo:</label>
    <input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($dispositivo['modelo']) ?>">
</div>

<div class="form-group">
    <label for="licencia_software">Licencia de Software:</label>
    <input type="text" id="licencia_software" name="licencia_software" value="<?= htmlspecialchars($dispositivo['licencia_software']) ?>">
</div>

<div class="form-group">
    <label for="fecha_venc_licencia">Fecha Vencimiento de Licencia:</label>
    <input type="date" id="fecha_venc_licencia" name="fecha_venc_licencia" value="<?= htmlspecialchars($dispositivo['fecha_venc_licencia']) ?>">
</div>

<div class="form-group">
    <label for="seguro_activo">¿Tiene Seguro Activo?</label>
    <select id="seguro_activo" name="seguro_activo">
        <option value="0" <?= $dispositivo['seguro_activo'] == '0' ? 'selected' : '' ?>>No</option>
        <option value="1" <?= $dispositivo['seguro_activo'] == '1' ? 'selected' : '' ?>>Sí</option>
    </select>
</div>

<div class="form-group">
    <label for="fecha_venc_seguro">Fecha Vencimiento de Seguro:</label>
    <input type="date" id="fecha_venc_seguro" name="fecha_venc_seguro" value="<?= htmlspecialchars($dispositivo['fecha_venc_seguro']) ?>">
</div>

<div class="form-group">
    <label for="responsable">Responsable:</label>
    <input type="text" id="responsable" name="responsable" value="<?= htmlspecialchars($dispositivo['responsable']) ?>">
</div>

<div class="form-group">
    <label for="observaciones">Observaciones:</label>
    <textarea id="observaciones" name="observaciones"><?= htmlspecialchars($dispositivo['observaciones']) ?></textarea>
</div>







            <button type="submit" class="btn-guardar">Guardar Cambios</button>
            <a href="listado.php" class="btn-cancelar">Cancelar</a>
        </form>
    </section>
</main>

<?php require_once '../../includes/footer.php'; ?>
