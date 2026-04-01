<?php 
namespace controllers;

use models\Ingrediente;
use services\IngredienteService;
use services\LocalizacionService;
use repositories\IngredienteRepository;
use helpers\Logger;
use Exception;

class IngredienteController extends BaseController {

    private IngredienteService $ingredienteService;
    private LocalizacionService $localizacionService;
    private IngredienteRepository $repository;

    public function __construct() {
        $this->ingredienteService = new IngredienteService();
        $this->localizacionService = new LocalizacionService();
        $this->repository = new IngredienteRepository();
    }

    /**
     * Muestra la página de listado de ingredientes con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $ingredientes = $this->ingredienteService->getAllIngredientes();
            $ingredientesPorCategoria = $this->ingredienteService->sortIngredientesPorCategoria();
            $localizaciones = $this->localizacionService->getAllLocalizaciones();

            // 2. CARGAR VISTA de todos los ingredientes
            $this->mostrar('ingredientes/ingredientes-zelda-breath-of-the-wild', [
                'ingredientes' => $ingredientes,
                'ingredientesPorCategoria' => $ingredientesPorCategoria,
                'localizaciones' => $localizaciones,
                'base_url' => BASE_URL
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error al cargar la página de ingredientes',
                'base_url' => BASE_URL
            ]);
        }
    }

    /**
     * Recibe los filtros vía POST y devuelve los ingredientes filtrados en JSON
     * @param array $postData Los datos completos de $_POST
     * @return void
     */
    public function filtrarIngredientes(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $categorias = $postData['ingrediente_categoria'] ?? [];
            $localizaciones_ids = $postData['localizaciones'] ?? [];
            $nombre = trim($postData['nombre'] ?? '');

            // 2. OBTENER IDs de ingredientes por categorías (si hay categorías seleccionadas)
            $ingredientes_ids = [];
            if (!empty($categorias)) {
                $ingredientes_ids = $this->ingredienteService->getIngredientesPorCategoria($categorias);
            }

            // 3. OBTENER INGREDIENTES que coincidan si hay búsqueda por nombre
            if (!empty($nombre)) {
                $ingredientes_por_nombre = $this->ingredienteService->buscarIngredientesPorNombre($nombre);
                $ids_por_nombre = array_map(function($ing) {
                    return $ing->getIdIngrediente();
                }, $ingredientes_por_nombre);
                
                // Si ya hay IDs por categoría, hacer intersección; si no, usar los de nombre
                if (!empty($ingredientes_ids)) {
                    $ingredientes_ids = array_intersect($ingredientes_ids, $ids_por_nombre);
                } else {
                    $ingredientes_ids = $ids_por_nombre;
                }
            }
            
            // 4. FILTRAR por localizaciones
            $ingredientes = $this->ingredienteService->getIngredientesFiltrados($ingredientes_ids, $localizaciones_ids);

            // 5. PREPARAR DATOS para pasar a Json
            $ingredientes_array = array_map(function($ingrediente) {
                return [
                    'id_ingrediente' => $ingrediente->getIdIngrediente(),
                    'nombre' => $ingrediente->getNombre(),
                    'imagen' => $ingrediente->getImagen(),
                    'descripcion' => $ingrediente->getDescripcion()
                ];
            }, $ingredientes);

            // 6. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'ingredientes' => $ingredientes_array]);
        
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error al filtrar los ingredientes']);
        }
    }

    /**
     * Recibe el ID de un ingrediente vía GET y devuelve sus detalles completos en JSON
     * @param array $getData Datos de $_GET
     * @throws Exception Si el ID del ingrediente no es válido
     * @return void
     */
    public function obtenerIngrediente(array $getData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($getData['id'] ?? 0);
            if ($id <= 0) { throw new Exception("ID de ingrediente no válido"); }

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $ingrediente = $this->ingredienteService->getIngredientePorId($id);
            if (!$ingrediente) {
                Logger::error("Ingrediente no encontrado con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Ingrediente no encontrado']);
            }

            // 3. DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'ingrediente' => [
                    'id_ingrediente' => $ingrediente->getIdIngrediente(),
                    'nombre' => $ingrediente->getNombre(),
                    'imagen' => $ingrediente->getImagen(),
                    'descripcion' => $ingrediente->getDescripcion()
                ]
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar los detalles del ingrediente'
            ]);
        }
    }
}
?>