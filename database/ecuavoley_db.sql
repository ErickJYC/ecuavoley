
-- Base de datos del proyecto Ecuavóley

CREATE DATABASE IF NOT EXISTS ecuavoley_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE ecuavoley_db;

-- Tabla para almacenar los mensajes de contacto
CREATE TABLE IF NOT EXISTS mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    asunto VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_registro DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
);
