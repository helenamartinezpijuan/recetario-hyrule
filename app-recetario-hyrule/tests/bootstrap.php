<?php
/**
 * Bootstrap para todos los tests
 * Configura el entorno de pruebas
 */

// Definir constantes para pruebas
define('TEST_MODE', true);
define('BASE_PATH', __DIR__ . '/../');
define('BASE_URL', '/recetario-hyrule');

// Configurar manejo de errores para tests
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

// Función de autocarga (misma que en home.php)
spl_autoload_register(function (string $class): void {
    $prefixToDirectory = [
        'config\\' => 'config/',
        'controllers\\' => 'controllers/',
        'helpers\\' => 'helpers/',
        'models\\' => 'models/',
        'repositories\\' => 'repositories/',
        'services\\' => 'services/'
    ];

    foreach ($prefixToDirectory as $namespace => $directory) {
        if (strpos($class, $namespace) === 0) {
            $className = substr($class, strlen($namespace));
            $file = BASE_PATH . $directory . $className . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Función auxiliar para mostrar resultados
function test_log($message, $type = 'info') {
    $colors = [
        'info' => "\033[0;36m",    // Cyan
        'success' => "\033[0;32m", // Green
        'error' => "\033[0;31m",   // Red
        'warning' => "\033[0;33m", // Yellow
    ];
    $reset = "\033[0m";
    $color = $colors[$type] ?? $colors['info'];
    
    echo $color . "[TEST] " . $message . $reset . PHP_EOL;
}

function test_assert($condition, $message, $type = 'error') {
    if ($condition) {
        test_log("✓ " . $message, 'success');
        return true;
    } else {
        test_log("✗ " . $message, $type);
        return false;
    }
}

function test_section($title) {
    echo PHP_EOL . "========================================" . PHP_EOL;
    echo "  " . $title . PHP_EOL;
    echo "========================================" . PHP_EOL;
}