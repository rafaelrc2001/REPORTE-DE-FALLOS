<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

// Verificar autenticación
redirectIfNotLoggedIn();

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Registrar datos recibidos para depuración
        file_put_contents('debug.log', "Datos recibidos:\n" . print_r($_POST, true) . "\n", FILE_APPEND);
        
        // Obtener y limpiar datos
        $nombre = trim($_POST['nombre']);
        $tipo = trim($_POST['tipo']);
        $fecha = $_POST['fecha'];
        $estado = trim($_POST['estado']);
        $ubicacion = trim($_POST['ubicacion']);
        $n_serie = trim($_POST['n_serie']);
        $marca = trim($_POST['marca']);
        $modelo = trim($_POST['modelo']);
        $licencia_software = trim($_POST['licencia_software']);
        $fecha_venc_licencia = $_POST['fecha_venc_licencia'] ?: null;
        $seguro_activo = isset($_POST['seguro_activo']) ? (int)$_POST['seguro_activo'] : 0;
        $fecha_venc_seguro = $_POST['fecha_venc_seguro'] ?: null;
        $responsable = trim($_POST['responsable']);
        $observaciones = trim($_POST['observaciones']);
       

                


        file_put_contents('debug.log', "Estado recibido: $estado\n", FILE_APPEND);

        // Validar estado
        $estadosPermitidos = ['Activo', 'En Reparación', 'Dado de Baja'];
        if (!in_array($estado, $estadosPermitidos)) {
            throw new Exception("Estado no válido: " . htmlspecialchars($estado));
        }

        // Forzar modo de error para PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Insertar en la base de datos
        $sql = "INSERT INTO dispositivos (
            nombre, tipo, fecha_adquisicion, estado, ubicacion,
            n_serie, marca, modelo, licencia_software, fecha_venc_licencia,
            seguro_activo, fecha_venc_seguro, responsable, observaciones 
        ) VALUES (
            :nombre, :tipo, :fecha, :estado, :ubicacion,
            :n_serie, :marca, :modelo, :licencia_software, :fecha_venc_licencia,
            :seguro_activo, :fecha_venc_seguro, :responsable, :observaciones
        )";

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

        $stmt->bindParam(':seguro_activo', $seguro_activo, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_venc_seguro', $fecha_venc_seguro);
        $stmt->bindParam(':responsable', $responsable);
        $stmt->bindParam(':observaciones', $observaciones);
        

        
        
        $stmt->execute();
        
        // Confirmar transacción
        $pdo->commit();
        
        // Redirigir con éxito
       
        header("Location: registrar.php?success=" . urlencode("Dispositivo registrado correctamente"));

        exit();
        
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // Registrar error detallado
        $errorMsg = "Error PDO: " . $e->getMessage() . "\n" . 
                   "Código: " . $e->getCode() . "\n" .
                   "Archivo: " . $e->getFile() . "\n" .
                   "Línea: " . $e->getLine() . "\n" .
                   "Trace: " . $e->getTraceAsString();
        
        file_put_contents('error.log', $errorMsg . "\n", FILE_APPEND);
        
        // Redirigir con mensaje de error
        header("Location: registrar.php?error=" . urlencode("Error al guardar en la base de datos") . 
              "&debug=" . urlencode($e->getMessage()));
        exit();
        
    } catch (Exception $e) {
        // Registrar error genérico
        file_put_contents('error.log', "Error General: " . $e->getMessage() . "\n", FILE_APPEND);
        
        
        header("Location: registrar.php?error=" . urlencode("Error al guardar en la base de datos"));

        exit();
    }
} else {
   

            // Redirigir con éxito (popup en registrar.php)
        header("Location: registrar.php?success=" . urlencode("Dispositivo registrado correctamente"));
        exit();

}
?>