<?php 
namespace controllers;

use models\Receta;
use services\RecetaService;
use repositories\RecetaRepository;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaController se encarga de:
 * Recibir datos del formulario de búsqueda
 * Llamar al services
 * Llamar a repositories
 * Cargar la vista
 */
class RecetaController {

    private $repository;
    private $service;

    public function __construct() {
        $this->service = new RecetaService();
        $this->repository = new RecetaRepository();
    }

    public function index() {
        $service = new RecetaService();
        
        $recetas = $service->getAllRecetas();
        $tiposEfectos = $service->getAllTiposEfectos();
        $ingredientesPorCategoria = $service->getIngredientesPorCategoria();
        
        //include __DIR__ . '/../views/recetas/index.php';
    }

    public function mostrarHome(): void {
        // Mostrar página /home
    }

    public function mostrarListadoRecetas(): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $orden = $_POST['orden_busqueda'] ?? '';

            // 2. BUSCAR en la base de datos
            $recetas = $this->repository->obtenerTodos($orden);

            // 3. MOSTRAR VISTA con los resultados
            $this->mostrar('recetas/index', [
                'recetas' => $recetas,
                'base_url' => BASE_URL
            ]);

        // 3. MANEJO DE ERRORES
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error en la búsqueda',
                'base_url' => BASE_URL
            ]);
        }
    }
    
    /**
     * Busca recetas aplicando los filtros del formulario
     * @param array $postData Los datos completos de $_POST
     * @return void
     */
    public function buscarConFiltros(array $postData): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $ingredientes = $postData['filtrar_ingredientes'] ?? [];
            $efectos = $postData['filtrar_efectos'] ?? [];
            
            // 2. VALIDAR Y NORMALIZAR los datos del formulario
            $filtros = $this->service->prepararFiltrosBusqueda($ingredientes, $efectos);
            
            // 3. BUSCAR en la base de datos
            $recetas = $this->repository->buscarPorFiltros($filtros);
            
            // 4. MOSTRAR VISTA con los resultados
            $this->mostrar('recetas/resultado', [
                'recetas' => $recetas,
                'base_url' => BASE_URL
            ]);

        // 5. MANEJO DE ERRORES    
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error en la búsqueda',
                'base_url' => BASE_URL
            ]);
        }
    }

    
    /**
     * Método auxiliar para cargar vistas
     * @param string $nombre Nombre del archivo de la vista
     * @param array $datos Array asociativo con los datos que la vista necesita
     * @throws Exception Si la vista no existe
     */
    private function mostrar(string $nombre, array $datos = []): void {
        // Construir ruta completa
        $rutaVista = __DIR__ . '/../views/' . $nombre . '.php';

        // Verificar que la vista existe
        if (!file_exists($rutaVista)) {
            throw new Exception("Vista no encontrada: $nombre");
        }

        // Convertir array asociativo en variables individuales
        extract($datos);

        // Incluir la vista
        require_once $rutaVista;
    }
}

?>