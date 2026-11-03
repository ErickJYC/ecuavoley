# Ecuavóley: Pasión Ecuatoriana

Sitio web educativo sobre el ecuavóley, desarrollado como parte de la asignatura **Programación de Plataformas Web** de la **Universidad Politécnica Salesiana (UPS)**.

**Estudiante:** Erick Josue Yunga Chimbo
**Año:** 2026

## Descripción

El proyecto presenta información sobre el ecuavóley, sus reglas, historia y características. También incorpora un formulario de contacto que permite registrar mensajes en una base de datos MariaDB mediante PHP.

El sistema dispone de un panel administrativo protegido mediante autenticación, sesiones PHP y un mecanismo de limitación de intentos de inicio de sesión.

Se ha probado el acceso público temporal al formulario utilizando ngrok, junto con la separación de archivos de configuración privados.

## Tecnologías utilizadas

- **HTML5:** estructura semántica del sitio web.
- **CSS3 y Flexbox:** presentación, distribución y diseño adaptable.
- **PHP:** procesamiento de formularios, validaciones y autenticación.
- **MariaDB:** almacenamiento de mensajes.
- **MySQLi:** conexión y consultas mediante PHP.
- **XAMPP:** servidor Apache y entorno de desarrollo local.
- **ngrok:** exposición temporal y controlada del servidor local mediante HTTPS.
- **Git y GitHub:** control de versiones y publicación del código.

## Secciones del sitio

- **Inicio:** presentación e introducción al ecuavóley.
- **Historia:** origen e importancia cultural.
- **Reglas:** principales reglas del deporte.
- **Posiciones:** funciones de los integrantes del equipo.
- **Galería:** imágenes y contenido multimedia.
- **Contacto:** formulario para registrar mensajes.

## Organización del proyecto

El desarrollo utiliza dos entornos locales:

**1. Proyecto de desarrollo**

Ubicado en `C:/xampp/htdocs/ecuavoley-html5/`. Contiene el código fuente del proyecto y los archivos administrativos.

**2. Aplicación pública**

Ubicada en `C:/xampp/ecuavoley-publico/`. Contiene los archivos necesarios para presentar la página y procesar el formulario de contacto mediante Apache en el puerto 8081.

La configuración privada se almacena fuera de ambas carpetas públicas.

## Estructura del proyecto de desarrollo

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
├── login.php
├── consultar.php
├── logout.php
├── sesion.php
├── css/
│   └── estilos.css
├── images/
├── audio/
├── database/
│   └── ecuavoley_db.sql
├── .gitignore
└── README.md
```

Los archivos privados se mantienen fuera del directorio público:

```text
C:/xampp/config-privada/
├── admin.php
├── config.local.php
├── publico/
│   └── conexion.php
└── login-intentos/
    └── archivos JSON de control
```

Esta estructura corresponde a la organización local utilizada durante las pruebas. Los archivos de configuración privados no deben publicarse en GitHub.

## Ejecución local mediante XAMPP

1. Colocar el proyecto dentro de `C:/xampp/htdocs/ecuavoley-html5/`.
2. Iniciar Apache y MariaDB desde XAMPP.
3. Crear o importar la base de datos `ecuavoley_db`.
4. Verificar que exista la tabla `mensajes`.
5. Configurar un usuario de MariaDB con los permisos mínimos necesarios.
6. Configurar las credenciales en `C:/xampp/config-privada/config.local.php`.
7. Configurar el administrador en el archivo privado `admin.php`, almacenando únicamente el hash de su contraseña.
8. Comprobar que las rutas de conexión utilizadas por PHP coincidan con las carpetas existentes.
9. Abrir `http://localhost:8080/ecuavoley-html5/`.

El script `database/ecuavoley_db.sql` permite crear la estructura inicial de la base de datos. Las credenciales y cuentas de MariaDB deben configurarse por separado.

## Formulario de contacto

El formulario utiliza el método HTTP `POST` para enviar los datos a `guardar.php`.

Antes de guardar un registro, PHP verifica que los campos obligatorios estén completos, que el correo tenga un formato válido y que las longitudes cumplan los límites establecidos.

| Campo | Máximo permitido |
|---|---:|
| Nombre | 100 caracteres |
| Correo electrónico | 150 caracteres |
| Asunto | 150 caracteres |
| Mensaje | 2000 caracteres |

Para insertar los mensajes se utilizan consultas preparadas mediante MySQLi.

Durante las pruebas se confirmó que el formulario puede almacenar información correctamente en MariaDB, tanto desde el entorno local como a través del túnel público de ngrok.

## Administración y autenticación

El proyecto dispone de los siguientes archivos:

- `login.php`: permite autenticar al administrador mediante usuario y contraseña.
- `sesion.php`: centraliza la configuración de las sesiones PHP y sus cookies.
- `consultar.php`: permite visualizar los mensajes únicamente si existe una sesión administrativa válida.
- `logout.php`: elimina los datos de sesión, invalida la cookie y redirige al formulario de acceso.

Las contraseñas se verifican mediante `password_verify()` y no se almacenan directamente en el código público.

Después de una autenticación correcta se regenera el identificador de sesión.

Además, se implementó un control de cinco intentos fallidos, con bloqueo temporal de cinco minutos, utilizando archivos JSON privados.

## Seguridad

Se aplicaron las siguientes medidas:

- Separación de las credenciales respecto al código público.
- Consultas preparadas para guardar los mensajes.
- Validación de entradas en el servidor.
- Escape de contenido mediante `htmlspecialchars()` al presentar datos.
- Protección de la consulta administrativa mediante sesiones.
- Regeneración del identificador de sesión al autenticarse.
- Cookies de sesión configuradas con `HttpOnly` y `SameSite=Lax`.
- Uso de cookies seguras cuando la conexión HTTPS es reconocida correctamente por PHP.
- Limitación de intentos fallidos de autenticación.
- Bloqueo de acceso HTTP directo a `conexion.php` en el entorno público.
- Desactivación del listado de directorios en el VirtualHost público.
- Exclusión de credenciales y archivos privados del repositorio público.

Estas medidas corresponden a un entorno educativo y no constituyen una auditoría de seguridad completa.

## Configuración de ngrok

Se instaló y configuró ngrok para publicar temporalmente el servidor local.

El túnel se dirige al servidor Apache del puerto `8081`, destinado a la aplicación pública.

```text
Internet (HTTPS)
       |
       v
     ngrok
       |
       v
Apache - Puerto 8081
       |
       v
ecuavoley-publico/
       |
       v
guardar.php
       |
       v
MariaDB - ecuavoley_db
```

El acceso administrativo se mantiene separado del directorio público utilizado para las pruebas de ngrok.

La URL de ngrok depende del túnel configurado y de que los servicios locales permanezcan en funcionamiento.

## Pruebas realizadas

Se efectuaron verificaciones de funcionamiento y acceso.

| Prueba | Resultado |
|---|---|
| Visualización de páginas HTML | Correcto |
| Envío de formularios mediante POST | Correcto |
| Almacenamiento de mensajes en MariaDB | Correcto |
| Acceso temporal mediante ngrok | Correcto |
| Bloqueo HTTP directo de conexion.php | 403 Forbidden |
| Acceso a rutas administrativas no publicadas | 404 Not Found |
| Bloqueo de rutas privadas y SQL comprobadas | 404 Not Found |
| Autenticación local del administrador | Correcto |
| Redirección sin sesión administrativa | Correcto |
| Cierre de sesión | Correcto |
| Validación sintáctica de archivos PHP comprobados | Sin errores |

Los resultados corresponden a las comprobaciones realizadas durante el desarrollo.

## Respaldo de la base de datos

Se realizó una exportación de la base de datos `ecuavoley_db` mediante phpMyAdmin.

Los respaldos locales se almacenan en:

`C:/xampp/respaldos-ecuavoley/`

Esta carpeta se mantiene fuera del repositorio público.

El script SQL del proyecto permite reconstruir la estructura inicial, mientras que los respaldos exportados pueden conservar los registros presentes en el momento de su creación, dependiendo de las opciones seleccionadas al exportar.

## Publicación

**Repositorio GitHub:**
https://github.com/ErickJYC/ecuavoley

**Versión estática — GitHub Pages:**
https://erickjyc.github.io/ecuavoley/

**Versión dinámica — ngrok (HTTPS):**
https://wasabi-chalice-tracing.ngrok-free.dev/

**Formulario de contacto público:**
https://wasabi-chalice-tracing.ngrok-free.dev/contacto.html

### Diferencias entre las versiones

- **GitHub Pages:** permite visualizar las páginas HTML, CSS y recursos multimedia, pero no ejecuta PHP ni MariaDB.
- **ngrok:** proporciona acceso temporal al servidor Apache local, permitiendo utilizar el formulario PHP y almacenar mensajes en MariaDB.
- **Panel administrativo:** permanece en el entorno local y no se publica mediante ngrok.

**Importante:** para que la versión dinámica funcione, deben permanecer encendidos el equipo, Apache, MariaDB y el túnel ngrok. El enlace público puede dejar de estar disponible si el túnel se detiene o cambia su configuración.

## Control de versiones

Se utiliza Git con la siguiente convención:

`FIX(###-UPS)`

Entre las actividades documentadas se encuentran:

- Implementación del sitio HTML5.
- Incorporación de estilos CSS.
- Desarrollo del formulario PHP.
- Conexión y almacenamiento mediante MariaDB.
- Autenticación del administrador.
- Mejoras de sesiones y seguridad.
- Configuración y pruebas de ngrok.
- Separación del entorno público y los archivos privados.
- Documentación de las verificaciones realizadas.

El siguiente commit previsto es `FIX(017-UPS)`, correspondiente a la actualización de la documentación y a los cambios de seguridad pendientes de revisión.

## Estado del proyecto

- [x] Desarrollo de las páginas HTML5.
- [x] Implementación de CSS y Flexbox.
- [x] Formulario de contacto.
- [x] Validaciones PHP.
- [x] Conexión a MariaDB.
- [x] Almacenamiento de mensajes.
- [x] Autenticación administrativa local.
- [x] Protección mediante sesiones.
- [x] Cierre de sesión.
- [x] Limitación de intentos de acceso implementada.
- [x] Configuración del túnel ngrok.
- [x] Pruebas de acceso público.
- [x] Separación de archivos privados.
- [x] Exportación de respaldo SQL.
- [ ] Revisión final de los archivos para GitHub.
- [ ] Publicación de los últimos cambios mediante Git.

## Autor

**Erick Josue Yunga Chimbo**
Universidad Politécnica Salesiana
Programación de Plataformas Web — 2026
