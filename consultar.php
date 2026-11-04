<?php

require_once __DIR__ . "/sesion.php";

// Verificar si el administrador inició sesión
if (!isset($_SESSION["admin_autenticado"]) ||
    $_SESSION["admin_autenticado"] !== true) {

    // Redirigir al inicio de sesión
    header("Location: login.php");
    exit;
}

// Conectar con la base de datos
require_once "conexion.php";

// Consultar todos los mensajes
$sql = "SELECT nombre, correo, asunto, mensaje, fecha_registro
        FROM mensajes
        ORDER BY fecha_registro DESC, id_mensaje DESC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Consultar mensajes | Ecuavóley</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header id="inicio">
        <h1>Ecuavóley: Pasión Ecuatoriana</h1>
        <p>Consulta de mensajes recibidos</p>
    </header>

    <div class="contenedor-flex">

        <nav aria-label="Navegación principal">
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="historia.html">Historia</a></li>
                <li><a href="reglas.html">Reglas</a></li>
                <li><a href="posiciones.html">Posiciones</a></li>
                <li><a href="galeria.html">Galería</a></li>
                <li><a href="contacto.html">Contacto</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>

        <main>
            <section>
                <h2>Mensajes recibidos</h2>

                <?php if ($resultado->num_rows === 0): ?>

                    <p class="destacado">
                        No existen mensajes registrados.
                    </p>

                <?php else: ?>

                    <div class="tabla-contenedor">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Asunto</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($fila = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8") ?></td>
                                        <td><?= htmlspecialchars($fila["correo"], ENT_QUOTES, "UTF-8") ?></td>
                                        <td><?= htmlspecialchars($fila["asunto"], ENT_QUOTES, "UTF-8") ?></td>
                                        <td><?= htmlspecialchars($fila["mensaje"], ENT_QUOTES, "UTF-8") ?></td>
                                        <td><?= htmlspecialchars($fila["fecha_registro"], ENT_QUOTES, "UTF-8") ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

            </section>
        </main>

    </div>

    <footer>
        <hr>
        <p>Estudiante: Erick Josue Yunga Chimbo</p>
        <p>&copy; 2026 - Todos los derechos reservados.</p>
    </footer>

</body>
</html>

<?php
$resultado->free();
$conexion->close();
?>
