<?php

use controllers\RecetaController;
use controllers\IngredienteController;
use controllers\EfectoController;
use controllers\LocalizacionController;

define('BASE_URL', 'recetario-hyrule');

/**
 * Función de autocarga de clases. La estructura del proyecto se compone de las vistas en HTML, el archivo de conexión a la base de datos, y los siguientes namespaces:
 *      models\Receta - Lógica de datos
 *      controllers\RecetaController - Lógica de control
 *      repositories\RecetaRepositorio - Acceso a datos
 *      services\RecetaService - Lógica de negocio
 */
spl_autoload_register(function (string $class): void {
    // Array que mapea los namespaces a las carpetas físicas
    $prefixToDirectory = [
        'config\\' => 'config/',
        'controllers\\' => 'controllers/',
        'helpers\\' => 'helpers/',
        'models\\' => 'models/',
        'repositories\\' => 'repositories/',
        'services\\' => 'services/'
    ];

    foreach ($prefixToDirectory as $namespace => $directory) {
        // Verificamos si la clase usa este namespace
        if (strpos($class, $namespace) === 0) {
            // Eliminamos el namespace para obtener sólo el nombre de la clase
            $className = substr($class, strlen($namespace));
            // Construimos la ruta completa
            $file = __DIR__ . '/../' . $directory . $className . '.php';
            
            // Incluimos el archivo si existe
            if (file_exists($file)) {
                require_once $file;
                return; // Salimos del bucle si encontramos la clase
            }
        }
    }
    
    // Si llegamos aquí, la clase no se encontró en ningún directorio
});


$action = $_POST['action'] ?? $_GET['action'] ?? 'home';

$receta_controller = new RecetaController();
$ingrediente_controller = new IngredienteController();
$efecto_controller = new EfectoController();
$localizacion_controller = new LocalizacionController();

switch ( true ) {
    case ($action === 'recetas'):
        $receta_controller->index();
        break;
    case ($action === 'filtrar_recetas' && isset($_POST['filtrar_recetas'])):
        //$receta_controller->buscarConFiltros($_POST);
        $receta_controller->filtrarRecetas($_POST);
        break;
    case ($action === 'obtener_receta' && isset($_POST['obtener_receta'])):
        $receta_controller->obtenerReceta($_POST);
        break;
    case ($action === 'home'):
        $receta_controller->index();
        break;
    default:
        // Página no encontrada
        echo "404";
}

?>