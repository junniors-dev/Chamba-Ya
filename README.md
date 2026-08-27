<div align="center">

<img src="assets/img/logo-chamba-ya.png" alt="Chamba Ya" width="180"/>

# Chamba&nbsp;Ya

**Tu chamba al toque — sin CV y con contacto directo**

Marketplace de empleo y servicios que conecta a trabajadores con quien los necesita.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=flat-square&logo=mysql&logoColor=white)
![MVC](https://img.shields.io/badge/MVC-sin%20frameworks-6366F1?style=flat-square)
![Dependencias](https://img.shields.io/badge/dependencias-0-64748B?style=flat-square)

</div>

---

## Qué es

Un portal de dos lados para el trabajo rápido e informal en Perú:

- **Soy trabajador** → encuentro ofertas cerca de mí y postulo en segundos.
- **Necesito ayuda** → busco gasfiteros, electricistas, limpieza o jardinería.

La misma cuenta sirve para las dos cosas. En lugar de separar las cuentas en "cliente" y
"trabajador", hay un **modo activo** que la persona cambia cuando quiere y que reordena su
panel sin quitarle capacidades.

## Funcionalidades

| | |
|---|---|
| **Búsqueda con filtros** | Texto, categoría, ubicación (departamento → provincia → distrito) y monto |
| **Avisos de trabajo y de servicio** | Dos flujos, cada uno con su identidad visual |
| **Solicitudes con estado** | Pendiente → Aceptado → Completado, con notificación en cada paso |
| **Reseñas verificables** | Solo entre quienes completaron un trabajo juntos |
| **Portafolio** | Trabajos anteriores con foto y categoría, en el perfil público |
| **Modo activo** | Alterna entre buscar chamba y contratar |
| **Panel de administración** | Usuarios, avisos, categorías y reportes |
| **Extras** | Favoritos, notificaciones, contacto por WhatsApp y reporte de avisos |

## Tecnologías

**PHP 8.2** · **MySQL/MariaDB** con PDO y consultas preparadas · **JavaScript vanilla** · **Apache** (XAMPP)

Sin Composer ni `node_modules`: se clona y funciona. El autoloader son 20 líneas propias
en `core/config/autoload.php`. La decisión es deliberada — el objetivo era entender qué
hace un framework por dentro, no delegarlo.

```
Chamba-Ya/
├── controllers/     Reciben la petición, validan y deciden
├── models/          Acceso a datos (PDO preparado)
├── views/           Solo presentación
├── core/
│   ├── config/      Configuración, sesiones y entorno
│   ├── db/          Conexión PDO
│   └── security/    CSRF y validación de entradas
└── database/        schema.sql, seed.sql y migraciones
```

## Instalación

Requiere XAMPP, o cualquier Apache con PHP 8.1+ y MySQL.

```bash
git clone https://github.com/junniors-dev/Chamba-Ya.git
```

```bash
mysql -u root -e "CREATE DATABASE bd_chamba_ya CHARACTER SET utf8mb4;"
mysql -u root bd_chamba_ya < database/schema.sql
mysql -u root bd_chamba_ya < database/seed.sql
```

```bash
cp core/config/env.example.php core/config/env.php
```

`env.php` guarda las credenciales y está en `.gitignore`. Abrir en
`http://localhost/Chamba-Ya/`.

**Cuentas de prueba** — contraseña `Chamba2026!` para las tres:
`ana.demo@chambaya.test` (administradora), `carlos.demo@chambaya.test`,
`lucia.demo@chambaya.test`.

<details>
<summary>¿Vienes de una versión anterior?</summary>

<br/>

```bash
mysql -u root bd_chamba_ya < database/migracion_seguridad.sql
mysql -u root bd_chamba_ya < database/migracion_funcionalidades.sql
```

</details>

## Seguridad

El proyecto pasó por una auditoría de autenticación, sesiones y control de acceso:
**14 vulnerabilidades encontradas y corregidas**, clasificadas según CWE y OWASP Top 10.

**El hallazgo crítico:** recuperar la contraseña solo pedía correo y teléfono. El problema
no era el código sino la premisa — *el teléfono se publica en los propios anuncios*, junto
al botón de WhatsApp. Los dos factores estaban a la vista en la misma web, sin límite de
intentos: cualquiera podía tomar cualquier cuenta. Se reemplazó por un enlace con token de
un solo uso, hasheado en base de datos y con caducidad de 30 minutos.

Los otros trece hallazgos, por severidad:

| | Corregido |
|---|---|
| **Crítica** | Ausencia de protección CSRF · Fijación de sesión |
| **Alta** | Cookie de sesión sin `httponly`/`samesite` · Bloqueo de intentos evadible borrando la cookie · Enumeración de usuarios |
| **Media** | Token de "recordarme" sin rotar · Desactivación de cuenta reversible sola · Borrado de cuenta sin contraseña · Reseñas sin trabajo previo · Sin validación en servidor · Redirección abierta · Credenciales en el código · Datos personales en el repositorio |

Cada corrección se verificó ejecutando la aplicación contra la base de datos real, no solo
leyendo el código. Eso destapó además cuatro defectos que la inspección estática no
mostraba, entre ellos que el buscador se rompía con consultas preparadas nativas y que PHP
y MySQL iban con 7 horas de diferencia, lo que hacía que un enlace de 30 minutos durase
siete horas y media.

<details>
<summary><b>Detalle completo de los 14 hallazgos</b></summary>

<br/>

| ID | Sev. | Hallazgo | Clasificación | Corrección |
|:---|:---|:---|:---|:---|
| CY-01 | Crítica | Restablecimiento de contraseña basado en datos públicos | CWE-640 · A07 | Token de un solo uso, hasheado, con caducidad |
| CY-02 | Crítica | Ausencia de protección CSRF, incluida la elevación a administrador | CWE-352 · A01 | Token por sesión con `hash_equals()` en formularios, controladores y AJAX |
| CY-03 | Crítica | Fijación de sesión | CWE-384 · A07 | `session_regenerate_id(true)` al autenticarse |
| CY-04 | Alta | Cookie de sesión sin atributos de seguridad | CWE-1004 · CWE-614 · A05 | `httponly`, `samesite`, `secure` bajo HTTPS y caducidad |
| CY-05 | Alta | Limitación de intentos aplicada en el cliente | CWE-307 · CWE-602 · A07 | Tabla `intento_login`, por correo e IP |
| CY-06 | Alta | Enumeración de usuarios por discrepancia de respuestas | CWE-204 · A07 | Respuesta uniforme y hash señuelo para igualar tiempos |
| CY-07 | Media | Token persistente sin rotación ni revocación | CWE-613 · A07 | Rotación en cada uso e invalidación al cambiar la contraseña |
| CY-08 | Media | Desactivación de cuenta revertida automáticamente | CWE-285 · A01 | Baja voluntaria y sanción administrativa se distinguen |
| CY-09 | Media | Eliminación de cuenta sin reautenticación | CWE-306 · A07 | Se exige la contraseña actual |
| CY-10 | Media | Calificaciones sin relación verificable | CWE-862 · A01 | Requiere postulación en estado `Completado` |
| CY-11 | Media | Validación de entrada ausente en el servidor | CWE-20 · A03 | `core/security/validacion.php` |
| CY-12 | Media | Redirección abierta | CWE-601 · A01 | Solo se aceptan rutas internas |
| CY-13 | Media | Credenciales embebidas y divulgación de errores | CWE-798 · CWE-209 · A05 | `env.php` fuera del repositorio; errores solo al log |
| CY-14 | Media | Datos personales en el repositorio público | CWE-538 · A01 | `schema.sql` y `seed.sql` con datos sintéticos |

**Ya era correcto antes de la auditoría:** contraseñas con `password_hash()`, PDO preparado
en los 13 modelos, salida escapada, control de propiedad de recursos y validación de
archivos subidos por tipo MIME real.

**Pendiente para producción:** HTTPS, un SMTP real, mover `assets/uploads/` fuera de la
raíz web, cabeceras CSP y un usuario de MySQL con privilegios mínimos.

</details>

## Autoría

**Mantenedor y desarrollador: [Junni Díaz — junniors-dev](https://github.com/junniors-dev)**

Chamba Ya se inició en junio de 2026 como proyecto de equipo. Desde julio de 2026 su
desarrollo y mantenimiento corresponden a un único responsable.

Son de autoría propia la auditoría de seguridad descrita arriba, el modo activo, el
portafolio, el ciclo de trabajo con estado `Completado` y el sistema de reseñas
verificables. El historial del repositorio documenta la etapa inicial.
