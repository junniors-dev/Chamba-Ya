# Chamba-Ya

<div align="center">

<img src="assets/img/logo-chamba-ya.png" alt="Chamba Ya" width="220"/>

# Chamba&nbsp;Ya

### Tu chamba al toque — sin CV y con contacto directo ⚡

Marketplace de empleo y servicios que conecta a **trabajadores** con **quien los necesita**, cerca y al instante.

<br/>

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Apache](https://img.shields.io/badge/Apache-XAMPP-FB7A24?style=for-the-badge&logo=apache&logoColor=white)

![Arquitectura](https://img.shields.io/badge/arquitectura-MVC%20sin%20frameworks-6366F1?style=flat-square)
![Sin dependencias](https://img.shields.io/badge/dependencias-ninguna-64748B?style=flat-square)
![Made in Peru](https://img.shields.io/badge/hecho%20en-Per%C3%BA%20%F0%9F%87%B5%F0%9F%87%AA-FFC700?style=flat-square)

</div>

---

## 🧭 ¿Qué es Chamba Ya?

Un portal **de dos lados** pensado para el trabajo rápido e informal:

- 🟢 **Soy trabajador** → encuentro ofertas cerca de mí y postulo en segundos.
- 🟡 **Necesito ayuda** → busco gasfiteros, electricistas, limpieza, jardinería y más, o publico mi necesidad.

Sin currículums, sin intermediarios: **contacto directo** entre las personas.

La misma cuenta sirve para las dos cosas. Quien pinta una fachada un martes puede
contratar a un gasfitero el jueves, así que no hay cuentas de "cliente" y cuentas
de "trabajador" por separado: hay un **modo activo** que la persona cambia cuando
quiere, y que decide qué panel y qué navegación ve.

---

## ✨ Funcionalidades

| | |
|---|---|
| 🔀 **Modo activo** | Cambia entre *buscar chamba* y *contratar*; reordena el panel sin quitar capacidades |
| 🔎 **Búsqueda con filtros** | Texto libre, categoría múltiple, ubicación (departamento → provincia → distrito) y monto mínimo |
| 📢 **Ofertas de trabajo y servicio** | Dos flujos diferenciados con su propia identidad visual |
| 📝 **Solicitudes con estado** | Pendiente → Aceptado → **Completado** (o Rechazado), con notificación en cada paso |
| 🖼️ **Portafolio** | Trabajos anteriores con foto, descripción y categoría, visibles en el perfil público |
| ⭐ **Calificaciones y reseñas** | Estrellas y comentario, **solo entre quienes completaron un trabajo juntos** |
| 🛠️ **Habilidades** | Etiquetas que describen al trabajador y aparecen en su perfil |
| 🔔 **Notificaciones** | Avisos con contador de no leídas, y correo opcional |
| 💚 **Guardados y favoritos** | Guarda anuncios y trabajadores para después |
| 💬 **Contacto por WhatsApp** | Comunicación directa, sin fricción |
| 🚩 **Reporte de anuncios** | Moderación para mantener la plataforma sana |
| 🛡️ **Panel de administración** | Usuarios, anuncios, categorías y reportes, con métricas |
| 🔐 **Cuentas seguras** | Registro, login, recuperación por enlace y gestión de cuenta |

---

## 🧱 Tecnologías

| Capa | Herramienta |
|---|---|
| Lenguaje | **PHP 8.2**, sin frameworks |
| Base de datos | **MySQL / MariaDB** (PDO con consultas preparadas reales) |
| Frontend | **HTML5, CSS3 y JavaScript vanilla** — sin build, sin npm |
| Servidor | **Apache** (XAMPP) |
| Iconos y fuentes | Font Awesome, Boxicons, Google Fonts |

No hay Composer ni `node_modules`: se clona y funciona. El autoloader de clases
son 20 líneas propias en `core/config/autoload.php`.

---

## 🗂️ Arquitectura

Patrón **MVC** escrito a mano. Cada capa hace una sola cosa:

```
Chamba-Ya/
├── index.php                  Portada y enrutado de las vistas públicas
├── controllers/               Reciben la petición, validan y deciden
├── models/                    Todo el acceso a datos (PDO preparado)
├── views/                     Solo presentación
│   ├── admin/                 Panel de administración
│   ├── anuncios/              Búsqueda y detalle
│   ├── auth/                  Login, registro y recuperación
│   ├── templates/             Cabecera, pie, sidebar reutilizables
│   └── user/                  Perfil y actividad del usuario
├── core/
│   ├── config/                config, sesiones, entorno y correo
│   ├── db/                    Conexión PDO
│   └── security/              CSRF y validación de entradas
├── database/                  schema.sql, seed.sql y migraciones
└── assets/                    CSS, JS e imágenes subidas
```

Dos detalles de diseño que conviene conocer antes de tocar el código:

- **`config.php` arranca la sesión.** `session_start()` tiene que ejecutarse antes
  de la primera salida HTML, y las vistas sueltas incluyen `config.php` en su
  primera línea. Es el único punto donde el arranque es seguro para todas.
- **`session.php` no incluye `config.php`.** Formarían un ciclo, porque `config.php`
  carga los helpers de seguridad y estos cargan `session.php`. Las dos funciones que
  necesitan `BASE_URL` (`requireLogin` y `requireAdmin`) lo cargan por su cuenta.

---

## 🚀 Instalación

Requiere **XAMPP** (o cualquier Apache con PHP 8.1+ y MySQL/MariaDB).

**1. Clonar dentro de `htdocs`**

```bash
git clone https://github.com/junniors-dev/Chamba-Ya.git
```

**2. Crear la base de datos**

```bash
mysql -u root -e "CREATE DATABASE bd_chamba_ya CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
```

**3. Cargar estructura y datos de ejemplo**

```bash
mysql -u root bd_chamba_ya < database/schema.sql
```

```bash
mysql -u root bd_chamba_ya < database/seed.sql
```

`seed.sql` trae los 25 departamentos, 196 provincias y 1874 distritos del Perú,
las categorías, las habilidades y tres cuentas de prueba.

**4. Configurar el entorno**

```bash
cp core/config/env.example.php core/config/env.php
```

Ajusta usuario y contraseña de MySQL si los tuyos no son los de XAMPP por defecto.
`env.php` está en `.gitignore`: **las credenciales nunca se suben al repositorio.**

**5. Abrir** `http://localhost/Chamba-Ya/`

### Cuentas de prueba

| Correo | Rol | Contraseña |
|---|---|---|
| `ana.demo@chambaya.test` | Administradora | `Chamba2026!` |
| `carlos.demo@chambaya.test` | Usuario | `Chamba2026!` |
| `lucia.demo@chambaya.test` | Usuario | `Chamba2026!` |

Son cuentas ficticias creadas solo para probar el proyecto.

### Si vienes de una versión anterior

Sobre una base de datos que ya existía, aplica las migraciones en orden:

```bash
mysql -u root bd_chamba_ya < database/migracion_seguridad.sql
```

```bash
mysql -u root bd_chamba_ya < database/migracion_funcionalidades.sql
```

---

## 🔐 Seguridad

El proyecto pasó por una auditoría de autenticación y manejo de sesiones. Esta
sección documenta **qué estaba mal y cómo se corrigió**, porque las decisiones
importan tanto como el resultado.

### Lo que ya estaba bien

- **Contraseñas hasheadas** con `password_hash()` (bcrypt) y verificadas con
  `password_verify()`. En ningún momento hubo contraseñas en texto plano.
- **Sin inyección SQL**: los 13 modelos usan PDO con consultas preparadas. Incluso
  los `WHERE` dinámicos del panel de administración construyen *placeholders*, nunca
  concatenan valores.
- **Salida escapada** con `htmlspecialchars()`, y listas blancas para los mensajes
  que llegan por la URL.
- **Comprobaciones de propiedad** antes de editar o borrar anuncios y postulaciones.
- **Subida de archivos validada** por extensión, tamaño y **tipo MIME real**
  (`finfo`), que es lo que impide colar un `.php` renombrado a `.jpg`.

### Vulnerabilidades corregidas

| Severidad | Problema | Corrección |
|---|---|---|
| 🔴 Crítica | **Toma de cuenta.** Recuperar la contraseña solo pedía correo + teléfono, y el teléfono se publica en los anuncios: los dos "secretos" estaban a la vista de cualquiera | Enlace con **token de un solo uso**, guardado hasheado (SHA-256) y con caducidad de 30 minutos |
| 🔴 Crítica | **Sin protección CSRF** en ningún formulario (29 en el momento de la auditoría), incluido *hacer administrador* a un usuario | Token por sesión comparado con `hash_equals()`, en todos los formularios, los 10 controladores y las peticiones AJAX |
| 🔴 Crítica | **Session fixation**: el identificador de sesión no cambiaba al iniciar sesión | `session_regenerate_id(true)` al entrar, al registrarse y al cambiar la contraseña |
| 🟠 Alta | **Cookie de sesión sin flags**, legible por JavaScript | `httponly`, `samesite=Lax`, `secure` bajo HTTPS, `use_strict_mode` y caducidad por inactividad |
| 🟠 Alta | **Bloqueo por intentos evadible**: el contador vivía en `$_SESSION`, es decir, del lado del atacante; bastaba borrar la cookie | Tabla `intento_login`, contando por correo **e** IP |
| 🟠 Alta | **Enumeración de usuarios**: la respuesta distinguía "correo no existe" de "contraseña incorrecta" | Mensaje idéntico en ambos casos, y comparación contra un hash ficticio para igualar también el tiempo de respuesta |
| 🟡 Media | **`remember_token` sin rotar** y superviviente al cambio de contraseña | Rota en cada uso y se invalida al cambiar la contraseña, en la misma consulta |
| 🟡 Media | **Desactivar la cuenta no servía**: se reactivaba sola al iniciar sesión, incluso tras una sanción | Se distingue la baja voluntaria (`Inactivo`, puede volver) de la sanción del administrador (`Suspendido` / `Bloqueado`, no entra) |
| 🟡 Media | **Borrar la cuenta con un clic** | Exige confirmar la contraseña actual |
| 🟡 Media | **Reseñas falsas**: cualquiera podía calificar a cualquiera sin haber trabajado juntos | Calificar exige una postulación **Completada** entre ambos, y cada reseña guarda de qué trabajo salió |
| 🟡 Media | **Sin validación en servidor** de correo, teléfono ni longitudes | `core/security/validacion.php`, aplicado en registro, edición y recuperación |
| 🟡 Media | **Open redirect** en el cambio de modo | Solo se aceptan rutas internas; cualquier URL absoluta o `//host` cae al inicio |
| 🟡 Media | **Credenciales en el código** y el error de conexión impreso al visitante | `env.php` fuera del repositorio y errores solo al log |
| 🟡 Media | **Volcado SQL con 11 correos y hashes reales** en un repositorio público, sin `.gitignore` | Sustituido por `schema.sql` + `seed.sql` con datos ficticios, y `.gitignore` añadido |

### Errores que aparecieron al verificar

Cuatro de ellos no se veían leyendo el código, solo ejecutándolo:

- **El buscador estaba roto** con consultas preparadas reales: repetía el mismo
  *placeholder* `:search` dos veces en una consulta, lo que MySQL rechaza.
- **PHP y MySQL iban con 7 horas de diferencia.** PHP escribía la fecha de
  caducidad y MySQL la comparaba con su propio `NOW()`, así que un enlace de 30
  minutos duraba 7 horas y media. Ahora los plazos los calcula la base de datos.
- **Dependencia circular** entre `config.php` y `session.php` que tumbaba la portada.
- **Rutas relativas `../`** en las vistas de autenticación, que dependían del
  directorio de trabajo y no del archivo.

### Cómo se verificó

No basta con que compile. Se comprobó ejecutando:

```
POST sin token CSRF                          → 419 rechazado
Contraseña incorrecta vs correo inexistente  → respuesta idéntica
PHPSESSID antes y después del login          → cambia
5 fallos borrando la cookie cada vez         → bloquea igual
Enlace de recuperación reutilizado           → rechazado
Contraseña antigua tras el reset             → rechazada
Calificar sin trabajo completado             → rechazado
Completar sin haber aceptado antes           → rechazado
Completar un trabajo ajeno                   → rechazado
Borrar un portafolio ajeno                   → rechazado
PHP renombrado a .png                        → rechazado por MIME real
Redirección a un dominio externo             → forzada al inicio
50 métodos de modelo contra la base real     → 50/50
22 páginas públicas, de usuario y de admin   → render completo, 0 errores PHP
```

### Lo que falta para producción

Esto es un proyecto de portafolio y corre en XAMPP. Antes de exponerlo a internet:

- Servir todo por **HTTPS** (la cookie `secure` ya se activa sola al detectarlo).
- Configurar un **SMTP real**; XAMPP no envía correos, y por eso en modo desarrollo
  el enlace de recuperación se muestra en pantalla. Con `modo_dev = false` eso no ocurre.
- Mover `assets/uploads/` fuera de la raíz web, o negar la ejecución de PHP en esa
  carpeta con una regla de Apache.
- Añadir cabeceras `Content-Security-Policy` y `X-Frame-Options`.
- Crear un usuario de MySQL con permisos mínimos, en lugar de `root`.

---

## 👥 Autoría

Proyecto desarrollado por **[junniors-dev](https://github.com/junniors-dev)**,
con contribuciones de [enzoliba](https://github.com/enzoliba).

---

<div align="center">

Hecho con 💚💛 para que encontrar chamba sea **al toque**.

</div>
