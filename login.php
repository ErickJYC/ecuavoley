
<?php
// Cargar la configuración compartida de sesiones
require_once __DIR__ . "/sesion.php";


// Si ya inició sesión, enviarlo a consultar.php
if (isset($_SESSION["admin_autenticado"]) &&
    $_SESSION["admin_autenticado"] === true) {
    header("Location: consultar.php");
    exit;
}

// Mensaje de error que se mostrará al usuario
$error = "";


// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST["usuario"] ?? "";
    $contrasena = $_POST["contrasena"] ?? "";

    // Configuración del límite de intentos
    $maxIntentos = 5;
    $tiempoBloqueo = 300; // 5 minutos
    $ahora = time();

    // Identificar la dirección IP del visitante
    $ip = $_SERVER["REMOTE_ADDR"] ?? "desconocida";

    // Archivo privado para registrar sus intentos
    $archivo = "C:/xampp/config-privada/login-intentos/"
             . hash("sha256", $ip) . ".json";

    $autenticado = false;

    // Abrir archivo sin borrar su contenido
    $fp = @fopen($archivo, "c+");

    if ($fp === false || !flock($fp, LOCK_EX)) {
        error_log("No se pudo registrar el intento de acceso.");
        $error = "No se puede procesar el acceso en este momento.";

        if ($fp !== false) {
            fclose($fp);
        }

    } else {

        // Leer el registro actual
        rewind($fp);
        $datos = json_decode(stream_get_contents($fp), true);

        if (!is_array($datos)) {
            $datos = [];
        }

        $intentos = (int) ($datos["intentos"] ?? 0);
        $inicio = (int) ($datos["inicio"] ?? $ahora);
        $bloqueadoHasta = (int) ($datos["bloqueado_hasta"] ?? 0);

        // Reiniciar después del periodo de cinco minutos
        if ($ahora - $inicio >= $tiempoBloqueo &&
            $bloqueadoHasta <= $ahora) {
            $intentos = 0;
            $inicio = $ahora;
            $bloqueadoHasta = 0;
        }

        if ($bloqueadoHasta > $ahora) {

            $error = "Demasiados intentos. Intenta nuevamente más tarde.";

        } else {

            // Cargar credenciales privadas
            $admin = require "C:/xampp/config-privada/admin.php";

            // Comprobar credenciales
            $autenticado =
                hash_equals($admin["usuario"], $usuario) &&
                password_verify(
                    $contrasena,
                    $admin["password_hash"]
                );

            if ($autenticado) {
                // Restablecer contador
                $intentos = 0;
                $inicio = $ahora;
                $bloqueadoHasta = 0;

            } else {
                $intentos++;

                if ($intentos >= $maxIntentos) {
                    $bloqueadoHasta = $ahora + $tiempoBloqueo;
                    $error = "Demasiados intentos. Intenta nuevamente más tarde.";
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        // Guardar los intentos en el servidor
        $registro = json_encode([
            "intentos" => $intentos,
            "inicio" => $inicio,
            "bloqueado_hasta" => $bloqueadoHasta
        ]);

        rewind($fp);
        $guardado = ftruncate($fp, 0) &&
                    fwrite($fp, $registro) === strlen($registro) &&
                    fflush($fp);

        flock($fp, LOCK_UN);
        fclose($fp);

        if (!$guardado) {
            $autenticado = false;
            $error = "No se puede procesar el acceso en este momento.";
            error_log("Error al guardar intentos de acceso.");
        }
    }

    if ($autenticado) {
        // Regenerar sesión después de autenticarse
        session_regenerate_id(true);
        $_SESSION["admin_autenticado"] = true;

        header("Location: consultar.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Acceso administrador | Ecuavóley</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header id="inicio">
        <h1>Ecuavóley: Pasión Ecuatoriana</h1>
        <p>Acceso al panel de administración</p>
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
            </ul>
        </nav>

        <main>
            <section>
                <h2>Iniciar sesión</h2>

                <p>
                    Ingresa tus credenciales para
                    consultar los mensajes recibidos.
                </p>

                <?php if ($error !== ""): ?>
                    <p role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                    </p>
                <?php endif; ?>

                <form action="login.php" method="POST">

                    <fieldset>
                        <legend>Datos de acceso</legend>

                        <label for="usuario">
                            Usuario:
                        </label>

                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            autocomplete="username"
                            required
                        >

                        <label for="contrasena">
                            Contraseña:
                        </label>

                        <input
                            type="password"
                            id="contrasena"
                            name="contrasena"
                            autocomplete="current-password"
                            required
                        >

                        <button type="submit">
                            Iniciar sesión
                        </button>

                    </fieldset>

                </form>
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
