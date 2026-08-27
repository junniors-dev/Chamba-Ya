<?php
/**
 * Plantilla de configuración local.
 *
 * Copia este archivo como env.php y ajusta los valores a tu entorno.
 * env.php está en .gitignore: nunca se sube al repositorio, para que las
 * credenciales reales no acaben publicadas en GitHub.
 */
return [
    'db_host'     => 'localhost',
    'db_nombre'   => 'bd_chamba_ya',
    'db_usuario'  => 'root',
    'db_password' => '',

    // true en tu máquina, false en producción.
    // En modo desarrollo se muestran los errores y el enlace de recuperación
    // de contraseña en pantalla (útil porque XAMPP no envía correos).
    'modo_dev'    => true,
];
