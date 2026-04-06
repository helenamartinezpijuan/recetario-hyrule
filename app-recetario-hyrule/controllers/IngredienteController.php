<?php 
namespace controllers;

use services\IngredienteService;
use services\LocalizacionService;
use helpers\Logger;
use Exception;

/**
 * La clase IngredienteController se encarga de recibir peticiones del index y conectar con el service para cargar la vista correspondiente, devolviendo respuestas JSON para peticiones AJAX
 */
class IngredienteController extends BaseController {

    private IngredienteService $service;
    private LocalizacionService $localizacionService;

    public function __construct() {
        $this->service = new IngredienteService();
        $this->localizacionService = new LocalizacionService();
    }

    /**
     * Muestra la página de listado de ingredientes con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $ingredientes = $this->service->getAllIngredientes();
            $ingredientesPorCategoria = $this->service->sortIngredientesPorCategoria();
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

            // 2. VALIDAR datos a través del service
            $ingredientes_ids = $this->service->getIngredientesPorCategoria($categorias);
            $ingredientes = $this->service->getIngredientesFiltrados($ingredientes_ids, $localizaciones_ids);

            // 3. PREPARAR DATOS para pasar a Json
            $ingredientes_array = array_map(function($ingrediente) {
                return [
                    'id_ingrediente' => $ingrediente->getIdIngrediente(),
                    'nombre' => $ingrediente->getNombre(),
                    'imagen' => $ingrediente->getImagen(),
                    'descripcion' => $ingrediente->getDescripcion()
                ];
            }, $ingredientes);

            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'ingredientes' => $ingredientes_array]);
        
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error al filtrar los ingredientes']);
        }
    }

    /**
     * Busca ingredientes por nombre (searchbar)
     * @param array $postData Los datos completos del $_POST
     * @return void
     */
    public function buscarIngredientes(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON
        header('Content-Type: application/json');
        
        try {
            // 1. EXTRAER DATOS del formulario
            $nombre = trim($postData['nombre'] ?? '');

            // 2. VALIDAR los datos a través del service
            $ingredientes = $this->service->buscarIngredientesPorNombre($nombre);
            
            // 3. PREPARAR DATOS para pasar a Json
            $ingredientes_array = array_map(function($receta) {
                return [
                    'id_ingrediente' => $receta->getIdIngrediente(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion()
                ];
            }, $ingredientes);
            
            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'ingredientes' => $ingredientes_array]);
            
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error en la búsqueda']);
        }
    }

    /**
     * Recibe el ID de un ingrediente vía GET y devuelve sus detalles completos en JSON
     * @param array $id_ingrediente Datos de $_GET
     * @throws Exception Si el ID del ingrediente no es válido
     * @return void
     */
    public function obtenerIngrediente(array $id_ingrediente): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($id_ingrediente['id'] ?? 0);
            if ($id <= 0) { throw new Exception("ID de ingrediente no válido"); }

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $ingrediente = $this->service->getIngredientePorId($id);
            if (!$ingrediente) {
                Logger::error("Ingrediente no encontrado con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Ingrediente no encontrado']);
                return;
            }

            // 3. PREPARAR DATOS para pasar a Json
            $localizaciones_array = $this->service->getIngredientesLocalizaciones($id);
            $localizaciones_filtradas = [];
            foreach ($localizaciones_array as $localizacion) {
                $localizaciones_filtradas[] = [
                    'id_localizacion' => $localizacion->getIdLocalizacion(),
                    'nombre' => $localizacion->getNombre(),
                    'region' => $localizacion->getRegion(),
                    'imagen' => $localizacion->getImagen(),
                    'descripcion' => $localizacion->getDescripcion()
                ];
            }

            // 4. DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'ingrediente' => [
                    'id_ingrediente' => $ingrediente->getIdIngrediente(),
                    'nombre' => $ingrediente->getNombre(),
                    'imagen' => $ingrediente->getImagen(),
                    'descripcion' => $ingrediente->getDescripcion(),
                    'localizaciones' => $localizaciones_filtradas
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