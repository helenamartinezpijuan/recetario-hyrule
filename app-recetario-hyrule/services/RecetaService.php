<?php 
namespace services;

use models\RecetaDetalle;
use repositories\RecetaRepository;
use repositories\EfectoRepository;
use services\IngredienteService;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaService se encarga de la validación y/o normalización de datos
 */
class RecetaService {
    private $recetaRepo;
    private $ingredienteService;
    private $efectoRepo;
    
    public function __construct() {
        $this->recetaRepo = new RecetaRepository();
        $this->ingredienteService = new IngredienteService();
        $this->efectoRepo = new EfectoRepository();
    }
    
    /**
     * Devuelve array con las categorías de ingredientes y sus ingredientes
     * @return array ['setas' => [Ingrediente, ...], 'pescados_mariscos' => [...], ...]
     */
    public function getIngredientesPorCategoria(): array {
        return $this->ingredienteService->getIngredientesPorCategoria();
    }
    
    /**
     * Obtiene todas las recetas (para la vista principal)
     * @return array de Receta
     */
    public function getAllRecetas(): array {
        return $this->recetaRepo->obtenerTodos();
    }
    
    /**
     * Obtiene todos los tipos de efecto (para los filtros)
     * @return array de TipoEfecto
     */
    public function getAllTiposEfectos(): array {
        return $this->efectoRepo->obtenerTodos();
    }
    
    /**
     * Obtiene recetas filtradas por IDs de efectos e ingredientes
     * @param array $efectos_ids
     * @param array $ingredientes_ids
     * @return array de objetos Receta
     */
    public function getRecetasFiltradas(array $efectos_ids, array $ingredientes_ids): array {
        return $this->recetaRepo->obtenerPorFiltros($efectos_ids, $ingredientes_ids);
    }
    
    /**
     * Obtiene el detalle completo de una receta (incluyendo ingredientes y efectos)
     * @param int $id Identificador único de la receta que buscamos
     * @return RecetaDetalle|null
     */
    public function getRecetaDetalle(int $id): ?RecetaDetalle {
        $receta = $this->recetaRepo->obtenerPorId($id);
        if (!$receta) return null;
        
        // Obtener ingredientes con cantidades (array asociativo)
        $ingredientes = $this->recetaRepo->obtenerIngredientesConCantidad($id);
        
        // Obtener efectos (array asociativo)
        $efectos = $this->recetaRepo->obtenerEfectosPorRecetaId($id);
        
        return new RecetaDetalle($receta, $ingredientes, $efectos);
    }
}

?>