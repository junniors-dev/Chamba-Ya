<?php
    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $projectRoot = str_replace('\\', '/', dirname(dirname(__DIR__)));

    // Calcula el nombre de la carpeta del proyecto automáticamente
    $folderName = str_replace($documentRoot, '', $projectRoot);

    // Define el base_path (Ejemplo: /Chamba-Ya-main/)
    define('BASE_URL', $folderName . '/');
?>
