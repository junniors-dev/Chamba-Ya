<?php
    // Calcula automáticamente la carpeta raíz del proyecto
    $projectFolder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base_path = ($projectFolder === '/' || $projectFolder === '\\') ? '/' : rtrim($projectFolder, '/');
?>
