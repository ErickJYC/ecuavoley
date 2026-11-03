
<?php
/*
=========================================
CIERRE DE SESIÓN - ECUAVÓLEY
=========================================
Cierra la sesión del administrador,
elimina sus datos y borra la cookie.
*/

// Cargar la configuración compartida
require_once __DIR__ . "/sesion.php";

// Vaciar los datos de la sesión
$_SESSION = [];

// Eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), "", [
        "expires" => time() - 42000,
        "path" => $params["path"],
        "domain" => $params["domain"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"],
        "samesite" => $params["samesite"] ?? "Lax"
    ]);
}

// Destruir la sesión del servidor
session_destroy();

// Evitar almacenar la respuesta en caché
header("Cache-Control: no-store");

// Volver al formulario de acceso
header("Location: login.php");
exit;
