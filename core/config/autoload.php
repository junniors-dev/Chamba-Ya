<?php
// Autoloader manual: carga las clases automáticamente, sin la necesidad de usar require_once
spl_autoload_register(function ($class) {
    $directorios = [
        __DIR__ . '/core/db/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/'
    ];

    foreach ($directorios as $directorio) {
        // Busca el archivo con el nombre exacto, con primera letra minúscula, o todo minúscula
        $archivos_posibles = [
            $directorio . $class . '.php',
            $directorio . lcfirst($class) . '.php',
            $directorio . strtolower($class) . '.php'
        ];

        foreach ($archivos_posibles as $archivo) {
            if (file_exists($archivo)) {
                require_once $archivo;
                return;
            }
        }
    }
});
