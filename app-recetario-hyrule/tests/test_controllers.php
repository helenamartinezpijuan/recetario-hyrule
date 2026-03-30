<?php
/**
 * Test de controladores
 * Verifica que los controladores instancian correctamente y tienen métodos
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE CONTROLADORES");

use controllers\RecetaController;
use controllers\IngredienteController;
use controllers\EfectoController;
use controllers\LocalizacionController;

$all_ok = true;

// 1. Test RecetaController
test_log("Probando RecetaController...");
try {
    $controller = new RecetaController();
    test_assert(true, "RecetaController instanciado correctamente");
    
    $methods = get_class_methods($controller);
    $expected = ['index', 'filtrarRecetas', 'obtenerReceta'];
    
    foreach ($expected as $method) {
        $exists = method_exists($controller, $method);
        test_assert($exists, "Método $method existe");
        if (!$exists) $all_ok = false;
    }
    
} catch (Exception $e) {
    test_assert(false, "Error instanciando RecetaController: " . $e->getMessage());
    $all_ok = false;
}

// 2. Test IngredienteController
test_log("Probando IngredienteController...");
try {
    $controller = new IngredienteController();
    test_assert(true, "IngredienteController instanciado correctamente");
    
    $methods = get_class_methods($controller);
    $expected = ['index', 'filtrarIngredientes', 'obtenerIngrediente'];
    
    foreach ($expected as $method) {
        $exists = method_exists($controller, $method);
        test_assert($exists, "Método $method existe");
        if (!$exists) $all_ok = false;
    }
    
} catch (Exception $e) {
    test_assert(false, "Error instanciando IngredienteController: " . $e->getMessage());
    $all_ok = false;
}

// 3. Test EfectoController
test_log("Probando EfectoController...");
try {
    $controller = new EfectoController();
    test_assert(true, "EfectoController instanciado correctamente");
    
    $methods = get_class_methods($controller);
    $expected = ['index', 'obtenerEfecto'];
    
    foreach ($expected as $method) {
        $exists = method_exists($controller, $method);
        test_assert($exists, "Método $method existe");
        if (!$exists) $all_ok = false;
    }
    
} catch (Exception $e) {
    test_assert(false, "Error instanciando EfectoController: " . $e->getMessage());
    $all_ok = false;
}

// 4. Test LocalizacionController
test_log("Probando LocalizacionController...");
try {
    $controller = new LocalizacionController();
    test_assert(true, "LocalizacionController instanciado correctamente");
    
    $methods = get_class_methods($controller);
    $expected = ['index', 'filtrarLocalizaciones', 'obtenerLocalizacion'];
    
    foreach ($expected as $method) {
        $exists = method_exists($controller, $method);
        test_assert($exists, "Método $method existe");
        if (!$exists) $all_ok = false;
    }
    
} catch (Exception $e) {
    test_assert(false, "Error instanciando LocalizacionController: " . $e->getMessage());
    $all_ok = false;
}

echo PHP_EOL;
test_assert($all_ok, "RESUMEN: Todos los controladores funcionan", $all_ok ? 'success' : 'error');