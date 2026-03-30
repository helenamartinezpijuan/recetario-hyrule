<?php 
namespace controllers;

use models\Efecto;
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

            // 2. CARGAR VISTA de todos los efectos
            $this->mostrar('efectos/index', [
                'efectos' => $efectos,
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
}
?>