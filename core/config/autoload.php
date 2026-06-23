<?php
// Autoloader manual: carga las clases automáticamente, sin la necesidad de usar require_once

// Calcula la raíz del proyecto (sube 2 niveles desde core/config/)
$projectRootAutoload = dirname(dirname(__DIR__));

spl_autoload_register(function ($class) use ($projectRootAutoload) {
    $directorios = [
        $projectRootAutoload . '/core/db/',
        $projectRootAutoload . '/models/',
        $projectRootAutoload . '/controllers/'
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
