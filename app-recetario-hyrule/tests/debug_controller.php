<?php
/**
 * Debugger para controladores
 * Permite depurar problemas específicos en un controlador
 */

require_once __DIR__ . '/bootstrap.php';

test_section("DEBUGGER DE CONTROLADORES");

// Configurar qué depurar
$debug_controller = $_GET['controller'] ?? 'RecetaController';
$debug_method = $_GET['method'] ?? 'index';
$debug_params = $_GET['params'] ?? [];

echo "🔍 Debuggeando: $debug_controller::$debug_method()" . PHP_EOL;
echo "📝 Parámetros: " . json_encode($debug_params) . PHP_EOL;
echo PHP_EOL;

try {
    $controller_class = "controllers\\$debug_controller";
    
    if (!class_exists($controller_class)) {
        throw new Exception("Controlador $controller_class no existe");
    }
    
    $controller = new $controller_class();
    
    echo "✅ Controlador instanciado correctamente" . PHP_EOL;
    echo "📋 Métodos disponibles: " . implode(', ', get_class_methods($controller)) . PHP_EOL;
    echo PHP_EOL;
    
    if (!method_exists($controller, $debug_method)) {
        throw new Exception("Método $debug_method no existe en $debug_controller");
    }
    
    echo "🚀 Ejecutando método $debug_method..." . PHP_EOL;
    echo "────────────────────────────────────────────" . PHP_EOL;
    
    // Capturar salida
    ob_start();
    
    if ($debug_method === 'index') {
        $controller->index();
    } elseif ($debug_method === 'filtrarRecetas' && !empty($debug_params)) {
        $controller->filtrarRecetas($debug_params);
    } elseif ($debug_method === 'obtenerReceta' && isset($debug_params['id'])) {
        $controller->obtenerReceta(['id' => $debug_params['id']]);
    } elseif ($debug_method === 'filtrarIngredientes' && !empty($debug_params)) {
        $controller->filtrarIngredientes($debug_params);
    } elseif ($debug_method === 'obtenerIngrediente' && isset($debug_params['id'])) {
        $controller->obtenerIngrediente(['id' => $debug_params['id']]);
    } else {
        echo "⚠️  Método no soportado para debug automático. Ejecutando directamente..." . PHP_EOL;
        $controller->$debug_method();
    }
    
    $output = ob_get_clean();
    
    echo PHP_EOL . "────────────────────────────────────────────" . PHP_EOL;
    echo "📤 OUTPUT:" . PHP_EOL;
    echo $output;
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo "📍 En: " . $e->getFile() . " línea " . $e->getLine() . PHP_EOL;
    echo PHP_EOL;
    echo "📚 Stack trace:" . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}