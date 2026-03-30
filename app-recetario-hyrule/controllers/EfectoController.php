<?php 
namespace controllers;

use models\Efecto;
use models\TipoEfecto;
use services\EfectoService;
use repositories\EfectoRepository;
use helpers\Logger;
use Exception;

class EfectoController extends BaseController {

    private EfectoService $service;
    private EfectoRepository $repository;

    public function __construct() {
        $this->service = new EfectoService();
        $this->repository = new EfectoRepository();
    }

    /**
     * Muestra la página de listado de efectos con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $efectos = $this->service->getAllEfectos();
            $tipos_efectos = $this->service->getAllTiposEfectos();

            // 2. CARGAR VISTA de todos los efectos
            $this->mostrar('efectos/index', [
                'efectos' => $efectos,
                'tipos_efectos' => $tipos_efectos,
                'base_url' => BASE_URL
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error al cargar la página de efectos',
                'base_url' => BASE_URL
            ]);
        }
    }

    /**
     * Recibe el ID de un efecto vía GET y devuelve sus detalles completos en JSON
     * @param array $getData Datos de $_GET
     * @throws Exception Si el ID del efecto no es válido
     * @return void
     */
    public function obtenerEfecto(array $getData): void {
        // Cabecera HTTP que informa al navegador que el contenido devuelto es JSON (no HTML)
        header('Content-Type: application/json');

        try {
            // 1. EXTRAER DATOS del formulario
            $id = (int)($getData['id'] ?? 0);
            if ($id <= 0) { throw new Exception("ID de efecto no válido"); }

            // 2. VALIDAR Y NORMALIZAR los datos a través del service
            $efecto = $this->service->getEfectoPorId($id);
            if (!$efecto) {
                Logger::error("Efecto no encontrado con id $id", __FILE__);
                echo json_encode(['success' => false, 'message' => 'Efecto no encontrado']);
            }

            // 3. PREPARAR DATOS para pasar a Json y DEVOLVER RESPUESTA
            echo json_encode([
                'success' => true,
                'efecto' => [
                    'id_efecto' => $efecto->getIdEfecto(),
                    'nombre' => $efecto->getTipoEfecto()->getNombre(),
                    'imagen' => $efecto->getImagen(),
                    'descripcion' => $efecto->getDescripcion()
                ]
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar los detalles del efecto'
            ]);
        }
    }
}
?>