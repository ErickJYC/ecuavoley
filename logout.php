
<?php
/*
=========================================
CIERRE DE SESIÓN - ECUAVÓLEY
=========================================
Este archivo permite cerrar la sesión
del administrador de manera segura.
*/

// Recuperar la sesión actual
session_start();

// Vaciar las variables de sesión
$_SESSION = [];

// Eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Regresar al inicio de sesión
header("Location: login.php");
exit;
