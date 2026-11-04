
<?php

// Cargar credenciales desde una carpeta fuera de htdocs
$config = require "C:/xampp/config-privada/config.local.php";



$conexion = new mysqli(
    $config["servidor"],    // Servidor de base de datos
    $config["usuario"],     // Usuario ecuavoley_app
    $config["contrasena"],  // Contraseña privada
    $config["base_datos"]   // Base de datos ecuavoley_db
);


if ($conexion->connect_error) {
    error_log("Error de conexión: " . $conexion->connect_error);
    exit("No se pudo conectar con la base de datos.");
}


if (!$conexion->set_charset("utf8mb4")) {
    error_log("Error al configurar UTF-8: " . $conexion->error);
    exit("No se pudo configurar la conexión.");
}


/*
=====================================================
CÓDIGO ANTERIOR - SOLO COMO REFERENCIA
=====================================================

ANTES:
La conexión utilizaba el usuario root y los datos
se escribían directamente en conexion.php.

Este fue nuestro primer método de conexión.
Lo dejamos comentado para recordar lo aprendido.

No debe utilizarse porque root tiene permisos
administrativos y la contraseña no debe quedar
escrita directamente en este archivo.


// Datos de conexión a la base de datos local

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "ecuavoley_db";


// Crear conexión utilizando MySQLi

$conexion = new mysqli(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos
);


// Configurar caracteres UTF-8

$conexion->set_charset("utf8mb4");

=====================================================
*/
