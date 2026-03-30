<?php
/**
 * Test de autocarga de clases
 * Verifica que todas las clases se cargan correctamente
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE AUTOCARGA DE CLASES");

$classes_to_test = [
    // Config
    'config\Database' => 'config/Database.php',
    
    // Helpers
    'helpers\Logger' => 'helpers/Logger.php',
    
    // Models
    'models\Receta' => 'models/Receta.php',
    'models\Ingrediente' => 'models/Ingrediente.php',
    'models\Efecto' => 'models/Efecto.php',
    'models\TipoEfecto' => 'models/TipoEfecto.php',
    'models\Localizacion' => 'models/Localizacion.php',
    'models\RecetaDetalle' => 'models/RecetaDetalle.php',
    
    // Repositories
    'repositories\BaseRepository' => 'repositories/BaseRepository.php',
    'repositories\RecetaRepository' => 'repositories/RecetaRepository.php',
    'repositories\IngredienteRepository' => 'repositories/IngredienteRepository.php',
    'repositories\EfectoRepository' => 'repositories/EfectoRepository.php',
    'repositories\LocalizacionRepository' => 'repositories/LocalizacionRepository.php',
    
    // Services
    'services\RecetaService' => 'services/RecetaService.php',
    'services\IngredienteService' => 'services/IngredienteService.php',
    'services\EfectoService' => 'services/EfectoService.php',
    'services\LocalizacionService' => 'services/LocalizacionService.php',
    
    // Controllers
    'controllers\BaseController' => 'controllers/BaseController.php',
    'controllers\RecetaController' => 'controllers/RecetaController.php',
    'controllers\IngredienteController' => 'controllers/IngredienteController.php',
    'controllers\EfectoController' => 'controllers/EfectoController.php',
    'controllers\LocalizacionController' => 'controllers/LocalizacionController.php',
];

$all_ok = true;

foreach ($classes_to_test as $class => $file) {
    $file_exists = file_exists(BASE_PATH . $file);
    test_assert($file_exists, "Archivo existe: $file");
    
    if ($file_exists) {
        try {
            $class_exists = class_exists($class);
            test_assert($class_exists, "Clase $class cargable");
            if (!$class_exists) $all_ok = false;
        } catch (Exception $e) {
            test_log("Error cargando $class: " . $e->getMessage(), 'error');
            $all_ok = false;
        }
    } else {
        $all_ok = false;
    }
}

echo PHP_EOL;
test_assert($all_ok, "RESUMEN: Todos los archivos y clases verificados", $all_ok ? 'success' : 'error');