<?php 
namespace controllers;

use services\LocalizacionService;
use helpers\Logger;
use Exception;

/**
 * La clase LocalizacionController se encarga de recibir peticiones del index y conectar con el service para cargar la vista correspondiente, devolviendo respuestas JSON para peticiones AJAX
 */
class LocalizacionController extends BaseController {

    private LocalizacionService $service;

    public function __construct() {
        $this->service = new LocalizacionService();
    }

    /**
     * Muestra la página de listado de localizaciones con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $localizaciones = $this->service->getAllLocalizaciones();
            $regiones = $this->service->getRegionesDisponibles();

            // 2. CARGAR VISTA de todas las localizaciones
            $this->mostrar('localizaciones/localizaciones-zelda-breath-of-the-wild', [
                'localizaciones' => $localizaciones,
                'regiones' => $regiones,
                'base_url' => BASE_URL
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('localizaciones/error', [
                'mensaje' => 'Error al cargar la página de localizaciones',
                'base_url' => BASE_URL
            ]);
        }
    }

    /**
     * Recibe los filtros vía POST y devuelve las localizaciones filtradas en JSON
     * @param array $postData Los datos completos de $_POST
     * @return void
     */
    public function filtrarLocalizaciones(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $regiones = $postData['regiones'] ?? [];

            // 2. VALIDAR datos a través del service
            $localizaciones = $this->service->getLocalizacionesFiltradas($regiones);

            // 3. PREPARAR DATOS para pasar a Json
            $localizaciones_array = array_map(function($localizacion) {
                return [
                    'id_localizacion' => $localizacion->getIdLocalizacion(),
                    'nombre' => $localizacion->getNombre(),
                    'region' => $localizacion->getRegion(),
                    'imagen' => $localizacion->getImagen(),
                    'descripcion' => $localizacion->getDescripcion()
                ];
            }, $localizaciones);

            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'localizaciones' => $localizaciones_array]);
        
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error al filtrar las localizaciones']);
        }
    }

    /**
     * Busca localizaciones por nombre (searchbar)
     * @param array $postData Los datos completos del $_POST
     * @return void
     */
    public function buscarLocalizaciones(array $postData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON
        header('Content-Type: application/json');
        
        try {
            // 1. EXTRAER DATOS del formulario
            $nombre = trim($postData['nombre'] ?? '');

            // 2. VALIDAR los datos a través del service
            $localizaciones = $this->service->buscarLocalizacionesPorNombre($nombre);
            
            // 3. PREPARAR DATOS para pasar a Json
            $localizaciones_array = array_map(function($localizacion) {
                return [
                    'id_receta' => $localizacion->getIdLocalizacion(),
                    'nombre' => $localizacion->getNombre(),
                    'imagen' => $localizacion->getImagen(),
                    'descripcion' => $localizacion->getDescripcion()
                ];
            }, $localizaciones);
            
            // 4. DEVOLVER RESPUESTA
            echo json_encode(['success' => true, 'localizaciones' => $localizaciones_array]);
            
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode(['success' => false, 'message' => 'Error en la búsqueda']);
        }
    }

    /**
     * Recibe el ID de una localizacion vía GET y devuelve sus detalles completos en JSON
     * @param array $id_localizacion Datos de $_GET
     * @throws Exception Si el ID de la localizacion no es válido
     * @return void
     */
    public function obtenerLocalizacion(array $id_localizacion): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($id_localizacion['id'] ?? 0);
            if ($id <= 0) { throw new Exception("ID de localizacion no válido"); }

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $localizacion = $this->service->getLocalizacionPorId($id);
            if (!$localizacion) {
                Logger::error("Localicazion no encontrada con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Localización no encontrada']);
            }

            // 3. DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'localizacion' => [
                    'id_localizacion' => $localizacion->getIdLocalizacion(),
                    'nombre' => $localizacion->getNombre(),
                    'imagen' => $localizacion->getImagen(),
                    'descripcion' => $localizacion->getDescripcion()
                ]
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar los detalles de la localización'
            ]);
        }
    }
}
?>