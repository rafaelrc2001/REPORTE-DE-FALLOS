<?php
session_start();
require_once 'db.php';

if (!isset($pdo)) {
    die("Error: Conexión a base de datos no disponible");
}

/**
 * Función para iniciar sesión
 * @param string $correo
 * @param string $contrasena
 * @return bool
 */
function loginUser($correo, $contrasena) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            session_regenerate_id(true);
            $_SESSION = [
                'user_id' => $user['id_usuario'],
                'user_name' => $user['nombre'],
                'user_role' => $user['cargo'],
                'last_activity' => time()
            ];
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica si hay una sesión activa
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Cierra la sesión de forma segura
 */
function logoutUser() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Redirige si no está logueado
 */
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: /auth/login.php?error=no_auth");
        exit();
    }
}

/**
 * Verifica rol de administrador
 */
function requireAdmin() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Administrador') {
        header("Location: /index.php?error=forbidden");
        exit();
    }
}

/**
 * Valida fortaleza de contraseña
 * @param string $password
 * @return bool
 */
function isSecurePassword($password) {
    return (strlen($password) >= 8 && 
            preg_match('/[A-Z]/', $password) && 
            preg_match('/[0-9]/', $password) &&
            preg_match('/[\W]/', $password));
}

/**
 * Control de tiempo de inactividad (30 minutos)
 */
function checkSessionExpiration() {
    $inactivity_limit = 1800; // 30 minutos en segundos
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity'] > $inactivity_limit)) {
        logoutUser();
        header("Location: /auth/login.php?error=inactivity");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Obtiene el rol del usuario actual
 * @return string|null
 */
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Verifica si el usuario tiene un rol específico
 * @param string $role
 * @return bool
 */
function hasRole($role) {
    return ($_SESSION['user_role'] ?? null) === $role;
}
?>