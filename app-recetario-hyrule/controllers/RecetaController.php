<?php 
namespace controllers;

use models\Receta;
use services\RecetaService;
use repositories\RecetaRepository;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaController se encarga de:
 * - Recibir datos del formulario de búsqueda
 * - Llamar al 'services'
 * - Llamar a 'repositories'
 * - Cargar la vista correspondiente de 'views'
 * - Devolver respuestas JSON para peticiones AJAX
 */
class RecetaController extends BaseController {

    private RecetaService $service;
    private RecetaRepository $repository;

    public function __construct() {
        $this->service = new RecetaService();
        //$this->repository = new RecetaRepository();
    }

    /****************************************************
     * MOSTRAR VISTA PRINCIPAL DE RECETAS (sin filtros) *
     ****************************************************/
    /**
     * Muestra la página de listado de recetas con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $recetas = $this->service->getAllRecetas();
            $tiposEfectos = $this->service->getAllTiposEfectos();
            $ingredientesPorCategoria = $this->service->getIngredientesPorCategoria();

            // 2. CARGAR VISTA de todas las recetas
            $this->mostrar('recetas/index', [
                'recetas' => $recetas,
                'tiposEfectos' => $tiposEfectos,
                'ingredientesPorCategoria' => $ingredientesPorCategoria,
                'base_url' => BASE_URL
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error al cargar la página de recetas',
                'base_url' => BASE_URL
            ]);
        }
    }

    /*************************************************************
     * ENDPOINT AJAX: FILTRAR RECETAS POR EFECTOS E INGREDIENTES *
     *************************************************************/
    /**
     * Recibe los filtros vía POST y devuelve las recetas filtradas en JSON
     * @param array $postData Los datos completos de $_POST
     * @return void
     */
    public function filtrarRecetas(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $efectos_ids = $postData['efectos'] ?? [];
            $ingredientes_ids = $postData['ingredientes'] ?? [];

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $recetas = $this->service->getRecetasFiltradas($efectos_ids, $ingredientes_ids);

            // 3. PREPARAR DATOS para pasar a Json
            $recetasArray = array_map(function($receta) {
                return [
                    'id_receta' => $receta->getIdReceta(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion()
                ];
            }, $recetas);

            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'recetas' => $recetasArray]);
        
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error al filtrar las recetas']);
        }
    }

    /*************************************************************
     * ENDPOINT AJAX: OBTENER DETALLE DE UNA RECETA (para modal) *
     *************************************************************/
    /**
     * Recibe el ID de una receta vía GET y devuelve sus detalles completos en JSON
     * @param array $getData Datos de $_GET
     * @throws Exception Si el ID de la receta no es válido
     * @return void
     */
    public function obtenerReceta(array $getData): void {
        // EXPLAIN WHAT THIS DOES
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($getData['id'] ?? 0);
            if ($id <= 0) { throw new Exception("ID de receta no válido"); }

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $detalle = $this->service->getRecetaDetalle($id);
            if (!$detalle) {
                Logger::error("Receta no encontrada con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
            }

            // 3. PREPARAR DATOS para pasar a Json
            $receta = $detalle->getReceta();
            $ingredientes = $detalle->getIngredientes();
            $efectos = $detalle->getEfectos();

            // 4. DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'receta' => [
                    'id_receta' => $receta->getIdReceta(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion(),
                    'ingredientes' => $ingredientes,
                    'efectos' => $efectos
                ]
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar los detalles de la receta'
            ]);
        }
    }
}

?>