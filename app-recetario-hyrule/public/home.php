<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/../');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/recetario-hyrule');
}

/**
 * Función de autocarga de clases. La estructura del proyecto se compone de las vistas en HTML, el archivo de conexión a la base de datos, y los siguientes namespaces:
 *      config\ - Conexión a la BD
 *      controllers\ - Lógica de control
 *      helpers\ - Sistema de log de errores
 *      models\ - Lógica de datos
 *      repositories\ - Acceso a datos
 *      services\ - Lógica de negocio
 */
spl_autoload_register(function (string $class): void {
    // 1. MAPEAR los namespaces a las carpetas físicas
    $prefixToDirectory = [
        'config\\' => 'config/',
        'controllers\\' => 'controllers/',
        'helpers\\' => 'helpers/',
        'models\\' => 'models/',
        'repositories\\' => 'repositories/',
        'services\\' => 'services/'
    ];

    foreach ($prefixToDirectory as $namespace => $directory) {
        // 2. VERIFICAR si la clase usa este namespace
        if (strpos($class, $namespace) === 0) {
            // 3. ELIMINAR el namespace para obtener sólo el nombre de la clase
            $className = substr($class, strlen($namespace));
            // 4. CONSTRUIR la ruta completa
            $file = BASE_PATH . $directory . $className . '.php';
            
            // 5. INCLUIR el archivo si existe
            if (file_exists($file)) {
                require_once $file;
                return; // Salimos del bucle si encontramos la clase
            }
        }
    } 
    // 6. Si llegamos aquí, la clase no se encontró en ningún directorio
});

use controllers\RecetaController;
use controllers\IngredienteController;
use controllers\EfectoController;
use controllers\LocalizacionController;


$action = $_POST['action'] ?? $_GET['action'] ?? 'home';

$receta_controller = new RecetaController();
$ingrediente_controller = new IngredienteController();
$efecto_controller = new EfectoController();
$localizacion_controller = new LocalizacionController();

switch ( $action ) {
    case 'recetas':
        $receta_controller->index();
        break;
    case 'filtrar_recetas':
        $receta_controller->filtrarRecetas($_POST);
        break;
    case 'obtener_receta':
        $receta_controller->obtenerReceta($_GET);
        break;
    case 'ingredientes':
        $ingrediente_controller->index();
        break;
    case 'filtrar_ingredientes':
        $ingrediente_controller->filtrarIngredientes($_POST);
        break;
    case 'obtener_ingrediente':
        $ingrediente_controller->obtenerIngrediente($_GET);
        break;
    case 'efectos':
        $efecto_controller->index();
        break;
    case 'obtener_efecto':
        $efecto_controller->obtenerEfecto($_GET);
        break;
    case 'localizaciones':
        $localizacion_controller->index();
        break;
    case 'filtrar_localizaciones':
        $localizacion_controller->filtrarLocalizaciones($_POST);
        break;
    case 'obtener_localizacion':
        $localizacion_controller->obtenerLocalizacion($_GET);
        break;
    case 'home':
        // Cargar vista home desde /views/home/index.php
        $rutaHome = BASE_PATH . 'views/home/index.php';
        if (file_exists($rutaHome)) {
            // Definir variables para la vista si son necesarias
            $activePage = 'home';
            $titulo = 'Inicio - Recetario de Hyrule';
            include $rutaHome;
        } else {
            // Fallback
            echo "<h1>Recetario de Hyrule</h1>";
            echo "<p><a href='?action=recetas'>Ver recetas</a></p>";
        }
        break;
    default:
        // Si la acción no existe, redirigir a home
        header('Location: index.php?action=home');
        exit;
}

?>