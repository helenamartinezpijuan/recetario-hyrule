<?php 
namespace controllers;

use services\RecetaService;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaController se encarga de recibir peticiones del index y conectar con el service para cargar la vista correspondiente, devolviendo respuestas JSON para peticiones AJAX
 */
class RecetaController extends BaseController {

    private RecetaService $service;

    public function __construct() {
        $this->service = new RecetaService();
    }

    /**
     * Muestra la página de listado de recetas con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $recetas = $this->service->getAllRecetas();
            $recetas_detalles = $this->obtenerRecetas();
            $tipos_efectos = $this->service->getAllTiposEfectos();
            $ingredientes_por_categoria = $this->service->sortIngredientesPorCategoria();

            // 2. CARGAR VISTA de todas las recetas
            $this->mostrar('recetas/recetas-zelda-breath-of-the-wild', [
                'recetas' => $recetas,
                'recetas_detalles' => $recetas_detalles,
                'tipos_efectos' => $tipos_efectos,
                'ingredientes_por_categoria' => $ingredientes_por_categoria,
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

            // 2. VALIDAR datos a través del service
            $recetas = $this->service->getRecetasFiltradas($efectos_ids, $ingredientes_ids);

            // 3. PREPARAR DATOS para pasar a Json
            $recetas_array = array_map(function($receta) {
                return [
                    'id_receta' => $receta->getIdReceta(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion()
                ];
            }, $recetas);

            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'recetas' => $recetas_array]);
        
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error al filtrar las recetas']);
        }
    }

    /**
     * Busca recetas por nombre (searchbar)
     * @param array $postData Los datos completos del $_POST
     * @return void
     */
    public function buscarRecetas(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON
        header('Content-Type: application/json');
        
        try {
            // 1. EXTRAER DATOS del formulario
            $nombre = trim($postData['nombre'] ?? '');

            // 2. VALIDAR los datos a través del service
            $recetas = $this->service->buscarRecetasPorNombre($nombre);
            
            // 3. PREPARAR DATOS para pasar a Json
            $recetas_array = array_map(function($receta) {
                return [
                    'id_receta' => $receta->getIdReceta(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion()
                ];
            }, $recetas);
            
            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'recetas' => $recetas_array]);
            
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error en la búsqueda']);
        }
    }

    /**
     * Recibe el ID de una receta vía GET y devuelve sus detalles completos en JSON
     * @param array $id_receta Datos de $_GET
     * @throws Exception Si el ID de la receta no es válido
     * @return void
     */
    public function obtenerReceta(array $id_receta): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($id_receta ?? 0);
            if ($id <= 0) { throw new Exception("ID de receta no válido"); }

            // 2. VALIDAR los datos a través del service
            $detalle = $this->service->getRecetaDetalle($id);
            if (!$detalle) {
                Logger::error("Receta no encontrada con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
            }

            // 3. PREPARAR DATOS para pasar a Json

            // 3.1. OBTENER DATOS del service
            $receta = $detalle->getReceta();
            $ingredientes_array = $detalle->getIngredientes();
            $efectos = $detalle->getEfectos();

            // 3.2. PREPARAR DATOS de ingredientes para JavaScript
            $ingredientes_preparados = [];
            foreach ($ingredientes_array as $ing) {
                $ingredientes_preparados[] = [
                    'id_ingrediente' => $ing['ingrediente']->getIdIngrediente(),
                    'nombre' => $ing['ingrediente']->getNombre(),
                    'imagen' => $ing['ingrediente']->getImagen(),
                    'descripcion' => $ing['ingrediente']->getDescripcion(),
                    'cantidad' => $ing['cantidad']
                ];
            }

            // 3.3. PREPARAR DATOS de efectos para JavaScript
            $efectos_preparados = [];
            foreach ($efectos as $efecto) {
                $efectos_preparados[] = [
                    'id_efecto' => $efecto->getIdEfecto(),
                    'nombre' => $efecto->getTipoEfecto()->getNombre(),
                    'imagen' => $efecto->getImagen(),
                    'descripcion' => $efecto->getDescripcion(),
                ];
            }

            // 4. DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'receta' => [
                    'id_receta' => $receta->getIdReceta(),
                    'nombre' => $receta->getNombre(),
                    'imagen' => $receta->getImagen(),
                    'descripcion' => $receta->getDescripcion(),
                    'ingredientes' => $ingredientes_preparados,
                    'efectos' => $efectos_preparados
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

    /**
     * Obtiene todas las recetas con detalles sobre sus efectoso
     * @return array{id_receta: int, nombre: string, imagen: string, descripcion: string, efectos: array>}
     */
    public function obtenerRecetas(): array {
        $recetas = $this->service->getAllRecetas();
        $recetas_detalle = [];

        foreach ($recetas as $_receta) {
            // 2. VALIDAR los datos a través del service
            $detalle = $this->service->getRecetaDetalle($_receta->getIdReceta());
            $receta = $detalle->getReceta();
            $efectos = $detalle->getEfectos();

            // 3. PREPARAR DATOS para array 
            $efectos_preparados = [];
            foreach ($efectos as $efecto) {
                $efectos_preparados[] = [
                    'id_efecto' => $efecto->getIdEfecto(),
                    'nombre' => $efecto->getTipoEfecto()->getNombre(),
                    'imagen' => $efecto->getImagen(),
                    'descripcion' => $efecto->getDescripcion(),
                ];
            }

            //
            $recetas_detalle[] = [
                'id_receta' => $receta->getIdReceta(),
                'nombre' => $receta->getNombre(),
                'imagen' => $receta->getImagen(),
                'descripcion' => $receta->getDescripcion(),
                'efectos' => $efectos_preparados
            ];
        }

        return $recetas_detalle;
    }
}

?>