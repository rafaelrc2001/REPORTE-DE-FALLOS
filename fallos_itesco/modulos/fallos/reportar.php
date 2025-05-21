<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

redirectIfNotLoggedIn();

// Obtener dispositivos activos para el select
try {
    $stmt = $pdo->query("SELECT id_dispositivo, nombre, ubicacion FROM dispositivos WHERE estado = 'Activo' ORDER BY nombre, ubicacion");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener dispositivos: " . $e->getMessage());
}

// Procesar formulario POST para reportar fallo
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_dispositivo = $_POST['id_dispositivo'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!$id_dispositivo || !$descripcion) {
            throw new Exception("Por favor, seleccione un dispositivo y escriba una descripción.");
        }

        // Verificar que el dispositivo existe y está activo
        $stmt = $pdo->prepare("SELECT id_dispositivo FROM dispositivos WHERE id_dispositivo = ? AND estado = 'Activo'");
        $stmt->execute([$id_dispositivo]);

        if (!$stmt->fetch()) {
            throw new Exception("El dispositivo seleccionado no existe o no está activo.");
        }

        // Insertar en tabla fallos
        $sql = "INSERT INTO fallos (id_dispositivo, descripcion, estado_fallo, fecha_reportado) 
                VALUES (:id_dispositivo, :descripcion, 'Pendiente', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_dispositivo' => $id_dispositivo,
            ':descripcion' => $descripcion
        ]);

        // Insertar en reportes
        $sql_reporte = "INSERT INTO reportes (fecha_reporte, tipo, descripcion) 
                        VALUES (NOW(), 'Fallo', :descripcion)";
        $stmt_reporte = $pdo->prepare($sql_reporte);
        $stmt_reporte->execute([':descripcion' => $descripcion]);

        $success_message = "Fallo reportado exitosamente.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Endpoint AJAX para obtener detalles del dispositivo
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'get_device_info') {
    header('Content-Type: application/json; charset=utf-8');

    $id = intval($_GET['id']); // Asegura que sea numérico

    try {
        $stmt = $pdo->prepare("SELECT * FROM dispositivos WHERE id_dispositivo = ? AND estado = 'Activo'");
        $stmt->execute([$id]);
        $device = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$device) {
            http_response_code(404);
            echo json_encode(['error' => 'Dispositivo no encontrado'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($device, JSON_UNESCAPED_UNICODE);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener el dispositivo'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reporte de Fallos | ITESCO</title>
    <link rel="stylesheet" href="../../assets/css/header.css">
    <style>
        /* Reset básico */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .main-content {
            padding: 20px;
            margin-top: 60px;
        }
        .container {
            max-width: 720px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 25px 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 25px;
            color: #004085;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #222;
        }
        select, textarea {
            width: 100%;
            padding: 10px 12px;
            font-size: 1rem;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            transition: border-color 0.25s ease-in-out;
            resize: vertical;
        }
        select:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px 0;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 15px 18px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-weight: 600;
            font-size: 1rem;
        }
        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        .success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        .device-info {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
            font-size: 0.9rem;
            line-height: 1.5;
            display: none;
        }
        .device-info h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #212529;
            font-weight: 700;
        }
        .device-info p {
            margin: 5px 0;
        }
        .device-info strong {
            color: #495057;
        }
        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }
            button {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="main-content">
        <main class="container" role="main" aria-labelledby="pageTitle">
            <h1 id="pageTitle">Reportar Fallo Técnico</h1>

            <?php if ($error_message): ?>
                <div class="message error" role="alert"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="message success" role="alert"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <form method="POST" id="falloForm" novalidate>
                <label for="id_dispositivo">Seleccione un dispositivo:</label>
                <select name="id_dispositivo" id="id_dispositivo" required aria-required="true" aria-describedby="deviceHelp">
                    <option value="" disabled selected>-- Seleccione un dispositivo --</option>
                    <?php foreach ($dispositivos as $d): ?>
                        <option value="<?= htmlspecialchars($d['id_dispositivo']) ?>">
                            <?= htmlspecialchars($d['nombre']) ?> - <?= htmlspecialchars($d['ubicacion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small id="deviceHelp" style="color:#6c757d;">Seleccione un dispositivo para ver su información.</small>

                <section id="device_info" class="device-info" aria-live="polite" aria-atomic="true">
                    <h3>Información del dispositivo</h3>
                    <p><strong>ID:</strong> <span id="info_id_dispositivo"></span></p>
                    <p><strong>Nombre:</strong> <span id="info_nombre"></span></p>
                    <p><strong>Tipo:</strong> <span id="info_tipo"></span></p>
                    <p><strong>Fecha Adquisición:</strong> <span id="info_fecha_adquisicion"></span></p>
                    <p><strong>Estado:</strong> <span id="info_estado"></span></p>
                    <p><strong>Ubicación:</strong> <span id="info_ubicacion"></span></p>
                    <p><strong>Número de Serie:</strong> <span id="info_n_serie"></span></p>
                    <p><strong>Marca:</strong> <span id="info_marca"></span></p>
                    <p><strong>Modelo:</strong> <span id="info_modelo"></span></p>
                    <p><strong>Licencia Software:</strong> <span id="info_licencia_software"></span></p>
                    <p><strong>Fecha Vencimiento Licencia:</strong> <span id="info_fecha_venc_licencia"></span></p>
                    <p><strong>Seguro Activo:</strong> <span id="info_seguro_activo"></span></p>
                    <p><strong>Fecha Vencimiento Seguro:</strong> <span id="info_fecha_venc_seguro"></span></p>
                    <p><strong>Responsable:</strong> <span id="info_responsable"></span></p>
                    <p><strong>Observaciones:</strong> <span id="info_observaciones"></span></p>
                    <p><strong>Fecha Registro:</strong> <span id="info_fecha_registro"></span></p>
                </section>

                <label for="descripcion">Descripción detallada del problema:</label>
                <textarea name="descripcion" id="descripcion" rows="6" required placeholder="Describe el problema aquí..." aria-required="true"></textarea>

                <button type="submit" aria-label="Enviar reporte de fallo">Reportar Fallo</button>
            </form>
        </main>
    </div>

    <script>
        (function() {
            const select = document.getElementById('id_dispositivo');
            const infoDiv = document.getElementById('device_info');

            select.addEventListener('change', () => {
                const id = select.value;

                if (!id) {
                    infoDiv.style.display = 'none';
                    return;
                }

                fetch(`?action=get_device_info&id=${encodeURIComponent(id)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('No se pudo obtener la información del dispositivo');
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) throw new Error(data.error);

                        // Mapear datos a los spans
                        const fields = [
                            'id_dispositivo', 'nombre', 'tipo', 'fecha_adquisicion', 'estado', 'ubicacion',
                            'n_serie', 'marca', 'modelo', 'licencia_software', 'fecha_venc_licencia',
                            'seguro_activo', 'fecha_venc_seguro', 'responsable', 'observaciones', 'fecha_registro'
                        ];

                        fields.forEach(field => {
                            const el = document.getElementById('info_' + field);
                            if (!el) return;

                            if (field === 'seguro_activo') {
                                el.textContent = data[field] == 1 ? 'Sí' : 'No';
                            } else {
                                el.textContent = data[field] ?? 'N/A';
                            }
                        });

                        infoDiv.style.display = 'block';
                    })
                    .catch(err => {
                        console.error(err);
                        infoDiv.style.display = 'none';
                    });
            });
        })();
    </script>
</body>
</html>