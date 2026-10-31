# Ecuavóley: Pasión Ecuatoriana

Sitio web educativo sobre el ecuavóley, desarrollado como parte de la asignatura **Programación de Plataformas Web** de la **Universidad Politécnica Salesiana (UPS)**.

**Estudiante:** Erick Josue Yunga Chimbo  
**Año:** 2026

## Descripción

El proyecto presenta información sobre el ecuavóley y permite enviar mensajes desde un formulario de contacto. En el entorno local incluye una base de datos para almacenar los mensajes y un acceso de administrador para consultarlos.

## Tecnologías

- HTML5 para la estructura de las páginas.
- CSS3 y Flexbox para los estilos y el diseño adaptable.
- PHP para procesar formularios, validar datos y gestionar sesiones.
- MariaDB y MySQLi para almacenar y consultar mensajes.
- XAMPP (Apache en el puerto 8080) como entorno local.
- Git y GitHub para control de versiones.

## Secciones del sitio

- **Inicio:** presentación del ecuavóley.
- **Historia:** antecedentes e importancia cultural.
- **Reglas:** información sobre la práctica del deporte.
- **Posiciones:** funciones de los jugadores.
- **Galería:** fotografías, video y audio.
- **Contacto:** formulario para enviar consultas.

## Estructura principal

```text
ecuavoley-html5/
├── index.html
├── historia.html
├── reglas.html
├── posiciones.html
├── galeria.html
├── contacto.html
├── conexion.php
├── guardar.php
├── consultar.php
├── login.php
├── logout.php
├── css/
│   └── estilos.css
├── images/
├── audio/
├── database/
│   └── ecuavoley_db.sql
├── .gitignore
└── README.md
```

Los archivos privados **no pertenecen al repositorio público**:

```text
C:/xampp/config-privada/
├── config.local.php   # Credenciales de MariaDB
└── admin.php          # Usuario y hash de contraseña del administrador
```

## Ejecutar en Windows con XAMPP

1. Colocar el proyecto en `C:/xampp/htdocs/ecuavoley-html5`.
2. Iniciar **Apache** y **MySQL/MariaDB** desde XAMPP.
3. Abrir phpMyAdmin e importar `database/ecuavoley_db.sql` para crear la base de datos y la tabla `mensajes`.
4. Crear un usuario de MariaDB exclusivo para el proyecto y otorgarle los permisos mínimos necesarios (`SELECT` e `INSERT`) sobre `ecuavoley_db`.
5. Crear localmente `C:/xampp/config-privada/config.local.php` con las claves `servidor`, `usuario`, `contrasena` y `base_datos`, utilizando los datos de acceso del entorno local.
6. Crear `C:/xampp/config-privada/admin.php` con el usuario administrador y un hash generado mediante `password_hash()`; no almacenar la contraseña en texto plano.
7. Comprobar que las rutas privadas utilizadas en `conexion.php` y `login.php` coincidan con la ubicación real de esos archivos.
8. Abrir `http://localhost:8080/ecuavoley-html5/`.

**Nota:** el archivo SQL crea la estructura; no crea automáticamente las cuentas y los archivos privados requeridos por PHP.

## Formulario de contacto

El formulario envía los datos a `guardar.php`, donde se validan antes de almacenarlos en MariaDB.

| Campo | Longitud máxima validada |
|---|---:|
| Nombre | 100 caracteres |
| Correo | 150 caracteres |
| Asunto | 150 caracteres |
| Mensaje | 2000 caracteres |

También se verifican los campos obligatorios y el formato del correo. Durante las pruebas se rechazaron entradas que superaban estos límites y direcciones de correo inválidas.

## Consulta y autenticación

- `login.php` recibe las credenciales del administrador y verifica la contraseña mediante `password_verify()`.
- `consultar.php` exige una sesión de administrador autenticada y muestra los mensajes registrados.
- `logout.php` limpia y destruye la sesión antes de redirigir al inicio de sesión.
- `conexion.php` carga la configuración de base de datos desde una carpeta fuera de `htdocs`.

## Seguridad y limitaciones

- No publicar `config.local.php`, `admin.php`, contraseñas ni volcados con datos personales reales.
- `.gitignore` excluye el archivo `config.local.php` local del repositorio.
- El usuario de MariaDB debe tener únicamente los permisos requeridos.
- Antes de permitir acceso desde Internet, revisar la configuración de sesiones, los límites de intentos de inicio de sesión y la protección del entorno XAMPP.
- Las rutas absolutas `C:/xampp/...` son específicas del equipo local y deben adaptarse al entorno de despliegue.

## Publicación

**Repositorio:** https://github.com/ErickJYC/ecuavoley  
**Versión estática (GitHub Pages):** https://erickjyc.github.io/ecuavoley/

GitHub Pages publica HTML, CSS e imágenes, pero **no ejecuta PHP ni MariaDB**. Las funciones de formulario con almacenamiento, login y consulta están disponibles en el servidor local XAMPP. Para compartir temporalmente el servidor local se estudiará ngrok después de completar las verificaciones de seguridad.

## Control de versiones

Se utiliza Git con commits identificados mediante el formato `FIX(###-UPS)`. El commit **`FIX(015-UPS)`** incorporó los archivos PHP, el script SQL, el inicio y cierre de sesión y las mejoras de configuración.

## Estado del desarrollo

- [x] Páginas informativas HTML5.
- [x] Estilos CSS y pruebas responsive.
- [x] Formulario y validaciones del lado del servidor.
- [x] Persistencia y consulta de mensajes en MariaDB.
- [x] Autenticación y cierre de sesión del administrador.
- [x] Cambios publicados en el repositorio.
- [ ] Revisión final de seguridad previa a ngrok.
- [ ] Pruebas del acceso temporal mediante ngrok.

## Autor

**Erick Josue Yunga Chimbo**  
Universidad Politécnica Salesiana — 2026.
