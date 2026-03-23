<?php

use controllers\ClienteController;
use controllers\FacturaController;
use controllers\MascotaController;
use controllers\TratamientoController;

define('BASE_URL', '/practicas-php/clinica_veterinaria');
define('APP_ENV', 'development');

/**
 * Función de autocarga de clases. La estructura del proyecto se compone de las vistas en HTML, el archivo de conexión a la base de datos, y los siguientes namespaces:
 *      models\Cliente - Lógica de datos
 *      controllers\ClienteController - Lógica de control
 *      repositories\ClienteRepositorio - Acceso a datos
 *      services\ClienteService - Lógica de negocio
 */
spl_autoload_register(function (string $class): void {
    // Array que mapea los namespaces a las carpetas físicas
    $prefixToDirectory = [
        'config\\' => 'config/',
        'controllers\\' => 'controllers/',
        'models\\' => 'models/',
        'repositories\\' => 'repositories/',
        'services\\' => 'services/',
        'helpers\\' => 'helpers/'
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


$action = $_POST['action'] ?? $_GET['action'] ?? 'mostrarFormularioPrincipal';

$cliente_controller = new ClienteController();
$mascota_controller = new MascotaController();
$factura_controller = new FacturaController();
$tratamiento_controller = new TratamientoController();

switch ( true ) {
    case ($action === 'buscarConFiltros' && isset($_POST['buscar_cliente'])):
        $cliente_controller->buscarConFiltros($_POST);
        break;
    case ($action === 'buscarSinFiltros' && isset($_POST['buscar_cliente_sin_filtro'])):
        $cliente_controller->buscarSinFiltros();
        break;
    case ($action === 'crear' && isset($_POST['crear_cliente'])):
        $cliente_controller->crear($_POST);
        break;
    case ($action === 'mostrarFormularioPrincipal'):
        $cliente_controller->mostrarFormularioPrincipal();
        break;
    default:
        // Página no encontrada
        echo "404";
}

?>