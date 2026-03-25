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

    /*********************************************************
     * MOSTRAR VISTA PRINCIPAL DE LOCALIZACIONES (sin filtros) *
     *********************************************************/
    /**
     * Muestra la página de listado de localizaciones con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $localizaciones = $this->service->getAllLocalizaciones();

            // 2. CARGAR VISTA de todas las localizaciones
            $this->mostrar('localizacions/index', [
                'localizaciones' => $localizaciones,
                'base_url' => BASE_URL
            ]);

        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('recetas/error', [
                'mensaje' => 'Error al cargar la página de localizaciones',
                'base_url' => BASE_URL
            ]);
        }
    }
}
?>