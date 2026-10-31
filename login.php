
<?php
// Iniciar la sesión del usuario
session_start();

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

    // Cargar credenciales privadas del administrador
    $admin = require "C:/xampp/config-privada/admin.php";

    // Comprobar usuario y contraseña
    if (
        hash_equals($admin["usuario"], $usuario) &&
        password_verify($contrasena, $admin["password_hash"])
    ) {
        // Regenerar el ID de sesión por seguridad
        session_regenerate_id(true);

        $_SESSION["admin_autenticado"] = true;

        // Redirigir al administrador
        header("Location: consultar.php");
        exit;
    }

    $error = "Usuario o contraseña incorrectos.";
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
