<?php
// tests/bootstrap.php
require_once __DIR__ . '/../helpers/Logger.php';

// Configurar entorno de pruebas
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader simple para pruebas
spl_autoload_register(function ($class) {
    $prefixes = [
        'config\\' => __DIR__ . '/../config/',
        'controllers\\' => __DIR__ . '/../controllers/',
        'models\\' => __DIR__ . '/../models/',
        'repositories\\' => __DIR__ . '/../repositories/',
        'services\\' => __DIR__ . '/../services/',
        'helpers\\' => __DIR__ . '/../helpers/'
    ];
    
    foreach ($prefixes as $prefix => $base_dir) {
        if (strpos($class, $prefix) === 0) {
            $relative_class = substr($class, strlen($prefix));
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }
    return false;
});