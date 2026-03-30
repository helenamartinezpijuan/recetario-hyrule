<?php
/**
 * Test de enrutamiento
 * Verifica que todas las rutas/acciones responden correctamente
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE ENRUTAMIENTO");

// Lista de rutas a probar
$routes = [
    'home' => ['action' => 'home', 'method' => 'GET'],
    'recetas' => ['action' => 'recetas', 'method' => 'GET'],
    'ingredientes' => ['action' => 'ingredientes', 'method' => 'GET'],
    'efectos' => ['action' => 'efectos', 'method' => 'GET'],
    'localizaciones' => ['action' => 'localizaciones', 'method' => 'GET'],
    'filtrar_recetas' => ['action' => 'filtrar_recetas', 'method' => 'POST', 'data' => ['efectos' => [], 'ingredientes' => []]],
    'filtrar_ingredientes' => ['action' => 'filtrar_ingredientes', 'method' => 'POST', 'data' => ['ingrediente_categoria' => [], 'localizaciones' => []]],
    'filtrar_localizaciones' => ['action' => 'filtrar_localizaciones', 'method' => 'POST', 'data' => ['regiones' => []]],
];

// Simular el enrutador
function simulate_router($action, $method, $data = []) {
    ob_start();
    
    try {
        $controller_map = [
            'recetas' => 'RecetaController',
            'filtrar_recetas' => 'RecetaController',
            'obtener_receta' => 'RecetaController',
            'ingredientes' => 'IngredienteController',
            'filtrar_ingredientes' => 'IngredienteController',
            'obtener_ingrediente' => 'IngredienteController',
            'efectos' => 'EfectoController',
            'obtener_efecto' => 'EfectoController',
            'localizaciones' => 'LocalizacionController',
            'filtrar_localizaciones' => 'LocalizacionController',
            'obtener_localizacion' => 'LocalizacionController',
            'home' => null,
        ];
        
        $controller_class = $controller_map[$action] ?? null;
        
        if ($controller_class) {
            $full_class = "controllers\\" . $controller_class;
            
            if (!class_exists($full_class)) {
                throw new Exception("Clase $full_class no existe");
            }
            
            $controller = new $full_class();
            
            $method_name = $action;
            if ($method === 'POST' && method_exists($controller, $method_name)) {
                $controller->$method_name($data);
            } elseif ($method === 'GET' && method_exists($controller, $method_name)) {
                $controller->$method_name();
            } else {
                throw new Exception("Método $method_name no encontrado en $controller_class");
            }
        } elseif ($action === 'home') {
            // Simular carga de home
            $home_file = BASE_PATH . 'views/home/index.php';
            if (file_exists($home_file)) {
                $activePage = 'home';
                $titulo = 'Inicio';
                include $home_file;
            } else {
                throw new Exception("Vista home no encontrada");
            }
        }
        
        $output = ob_get_clean();
        return ['success' => true, 'output_length' => strlen($output)];
        
    } catch (Exception $e) {
        ob_end_clean();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

$all_ok = true;

foreach ($routes as $route_name => $route) {
    $result = simulate_router($route['action'], $route['method'], $route['data'] ?? []);
    
    // CORRECCIÓN: Usar concatenación normal o {$var} en lugar de ${var}
    if ($result['success']) {
        test_assert(true, "Ruta '$route_name' (" . $route['method'] . ") responde correctamente. Output: " . $result['output_length'] . " bytes");
    } else {
        test_assert(false, "Ruta '$route_name' (" . $route['method'] . ") falló: " . ($result['error'] ?? 'Error desconocido'));
        $all_ok = false;
    }
}

echo PHP_EOL;
test_assert($all_ok, "RESUMEN: Todas las rutas verificadas", $all_ok ? 'success' : 'error');