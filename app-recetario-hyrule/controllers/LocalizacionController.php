<?php 
namespace controllers;

use models\Localizacion;
use services\LocalizacionService;
use repositories\LocalizacionRepository;
use helpers\Logger;
use Exception;

class LocalizacionController extends BaseController {

    private LocalizacionService $service;
    private LocalizacionRepository $repository;

    public function __construct() {
        $this->service = new LocalizacionService();
        $this->repository = new LocalizacionRepository();
    }

    /**
     * Muestra la página de listado de localizaciones con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $localizaciones = $this->service->getAllLocalizaciones();

            // 2. CARGAR VISTA de todas las localizaciones
            $this->mostrar('localizaciones/localizaciones-zelda-breath-of-the-wild', [
                'localizaciones' => $localizaciones,
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
}
?>