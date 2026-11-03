
<?php
/*
=========================================
CONFIGURACIÓN DE SESIONES - ECUAVÓLEY
=========================================
*/

ini_set("session.use_strict_mode", "1");
ini_set("session.use_only_cookies", "1");

// Detectar HTTPS directo
$https = !empty($_SERVER["HTTPS"]) &&
         $_SERVER["HTTPS"] !== "off";

// Ruta de la cookie según la ubicación
$path = "/";

if (
    isset($_SERVER["SCRIPT_NAME"]) &&
    str_starts_with(
        $_SERVER["SCRIPT_NAME"],
        "/ecuavoley-html5/"
    )
) {
    $path = "/ecuavoley-html5/";
}

session_set_cookie_params([
    "lifetime" => 0,
    "path" => $path,
    "secure" => $https,
    "httponly" => true,
    "samesite" => "Lax"
]);

session_start();
