<?php 
namespace controllers;

use models\Ingrediente;
use services\IngredienteService;
use repositories\IngredienteRepository;
use helpers\Logger;
use Exception;

class IngredienteController extends BaseController {

    private IngredienteService $service;
    private IngredienteRepository $repository;

    public function __construct() {
        $this->service = new IngredienteService();
        $this->repository = new IngredienteRepository();
    }

    /*********************************************************
     * MOSTRAR VISTA PRINCIPAL DE INGREDIENTES (sin filtros) *
     *********************************************************/
    /**
     * Muestra la página de listado de ingredientes con todos los datos iniciales
     * @return void
     */
    public function index(): void {
        try {
            // 1. OBTENER DATOS para la vista
            $ingredientes = $this->service->getAllIngredientes();
            $ingredientesPorCategoria = $this->service->getIngredientesPorCategoria(); // ?? EN REALIDAD ESTÁ EN EL SERVICE DE RECETAS

            // 2. CARGAR VISTA de todos los ingredientes
            $this->mostrar('ingredientes/index', [
                'ingredientes' => $ingredientes,
                'ingredientesPorCategoria' => $ingredientesPorCategoria,
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
}
?>