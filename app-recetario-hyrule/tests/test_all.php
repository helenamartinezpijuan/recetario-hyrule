<?php
/**
 * Ejecuta todos los tests en orden
 */

echo PHP_EOL;
echo "╔══════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║     RECETARIO HYRULE - SUITE DE TESTS COMPLETA              ║" . PHP_EOL;
echo "╚══════════════════════════════════════════════════════════════╝" . PHP_EOL;

$tests = [
    'Autocarga de clases' => __DIR__ . '/test_autoload.php',
    'Enrutamiento' => __DIR__ . '/test_routes.php',
    'Base de datos' => __DIR__ . '/test_database.php',
    'Repositorios' => __DIR__ . '/test_repositories.php',
    'Servicios' => __DIR__ . '/test_services.php',
    'Controladores' => __DIR__ . '/test_controllers.php',
];

$results = [];

foreach ($tests as $name => $file) {
    echo PHP_EOL;
    echo str_repeat('─', 70) . PHP_EOL;
    echo "▶️  EJECUTANDO: $name" . PHP_EOL;
    echo str_repeat('─', 70) . PHP_EOL;
    
    if (file_exists($file)) {
        include $file;
        $results[$name] = true;
    } else {
        echo "❌ Archivo no encontrado: $file" . PHP_EOL;
        $results[$name] = false;
    }
}

echo PHP_EOL;
echo "╔══════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║                    RESUMEN FINAL                             ║" . PHP_EOL;
echo "╚══════════════════════════════════════════════════════════════╝" . PHP_EOL;

$all_passed = true;
foreach ($results as $name => $passed) {
    $status = $passed ? "✅ PASÓ" : "❌ FALLÓ";
    echo "   $status - $name" . PHP_EOL;
    if (!$passed) $all_passed = false;
}

echo PHP_EOL;
if ($all_passed) {
    echo "🎉 ¡TODOS LOS TESTS PASARON CORRECTAMENTE! 🎉" . PHP_EOL;
} else {
    echo "⚠️  ALGUNOS TESTS FALLARON. Revisa los errores arriba. ⚠️" . PHP_EOL;
}