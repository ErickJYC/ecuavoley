
<?php
// 1. Verificar que los datos lleguen mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Metodo no permitido. Debes enviar el formulario.");
}

// 2. Recibir los datos y eliminar espacios exteriores
$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$asunto = trim($_POST["asunto"] ?? "");
$mensaje = trim($_POST["mensaje"] ?? "");

// 3. Preparar la lista de errores
$errores = [];

// Comprobar campos obligatorios
if ($nombre === "") {
    $errores[] = "El nombre es obligatorio.";
}

if ($correo === "") {
    $errores[] = "El correo es obligatorio.";
}

if ($asunto === "") {
    $errores[] = "El asunto es obligatorio.";
}

if ($mensaje === "") {
    $errores[] = "El mensaje es obligatorio.";
}

// 4. Validar el formato del correo
if ($correo !== "" && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electronico no es valido.";
}

// 5. Validar las longitudes, Cuenta caracteres, incluyendo letras acentuadas.
if (mb_strlen($nombre, "UTF-8") > 100) {
    $errores[] = "El nombre supera los 100 caracteres.";
}

if (mb_strlen($correo, "UTF-8") > 150) {
    $errores[] = "El correo supera los 150 caracteres.";
}

if (mb_strlen($asunto, "UTF-8") > 150) {
    $errores[] = "El asunto supera los 150 caracteres.";
}

if (mb_strlen($mensaje, "UTF-8") > 2000) {
    $errores[] = "El mensaje supera los 2000 caracteres.";
}

// Función para mostrar mensajes de forma segura
function mostrarTexto($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}

$titulo = "Resultado del formulario";
$correcto = false;

// 6. Comprobar si existen errores
if (!empty($errores)) {
    http_response_code(422);
} else {

    // 7. Conectar y guardar mediante consulta preparada
    try {
        require_once "conexion.php";

        $sql = "INSERT INTO mensajes
                (nombre, correo, asunto, mensaje)
                VALUES (?, ?, ?, ?)";

        $sentencia = $conexion->prepare($sql);

        $sentencia->bind_param(
            "ssss",
            $nombre,
            $correo,
            $asunto,
            $mensaje
        );

        if ($sentencia->execute()) {
            $correcto = true;
            $titulo = "Mensaje enviado correctamente";
        } else {
            throw new Exception("No se pudo guardar el mensaje.");
        }

        $sentencia->close();
        $conexion->close();

    } catch (Throwable $error) {
        http_response_code(500);
        $errores[] = "No fue posible guardar el mensaje. Intentalo nuevamente.";
        error_log($error->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Resultado | Ecuavóley</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header id="inicio">
        <h1>Ecuavóley: Pasión Ecuatoriana</h1>
        <p>Resultado del formulario de contacto</p>
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
                <h2><?= mostrarTexto($titulo) ?></h2>

                <?php if ($correcto): ?>

                    <p class="destacado">
                        Tu mensaje se ha guardado correctamente
                        en la base de datos.
                    </p>

                    <p>
                        <a href="consultar.php">
                            Ver mensajes registrados
                        </a>
                    </p>

                <?php else: ?>

                    <p>No se pudo procesar el formulario:</p>

                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= mostrarTexto($error) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <p>
                        <a href="contacto.html">
                            Volver al formulario
                        </a>
                    </p>

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
