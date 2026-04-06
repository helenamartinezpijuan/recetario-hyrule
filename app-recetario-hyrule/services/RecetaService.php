<?php 
namespace services;

use models\Receta;
use models\RecetaDetalle;
use repositories\RecetaRepository;
use services\IngredienteService;
use services\EfectoService;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaService se encarga de la validación y/o normalización de datos
 */
class RecetaService {
    private $recetaRepo;
    private $ingredienteService;
    private $efectoService;
    
    public function __construct() {
        $this->recetaRepo = new RecetaRepository();
        $this->ingredienteService = new IngredienteService();
        $this->efectoService = new EfectoService();
    }
    
    /**
     * Obtiene todas las recetas (para la vista principal)
     * @return Receta[]
     */
    public function getAllRecetas(): array {
        try {
            return $this->recetaRepo->obtenerTodos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Busca recetas por nombre o descripción
     * @param string $nombre Nombre de la receta buscada
     * @return Receta[]
     */
    public function buscarRecetasPorNombre(string $nombre): array {
        try {
            if (empty(trim($nombre))) {
                return $this->getAllRecetas();
            }
            return $this->recetaRepo->buscarPorNombre(trim($nombre));
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Obtiene una receta por su ID
     * @param int $id Identificador único de la receta
     * @return Receta|null
     */
    public function getRecetaPorId(int $id): ?Receta {
        try {
            return $this->recetaRepo->obtenerPorId($id);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return null;
        }
    }
        
    /**
     * Obtiene recetas filtradas por IDs de efectos e ingredientes
     * @param array $efectos_ids
     * @param array $ingredientes_ids
     * @return Receta[]
     */
    public function getRecetasFiltradas(array $efectos_ids, array $ingredientes_ids): array {
        try {
            if (empty($efectos_ids) && empty($ingredientes_ids)) {
                return $this->getAllRecetas();
            }
            return $this->recetaRepo->obtenerPorFiltros($efectos_ids, $ingredientes_ids);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
    
    /**
     * Obtiene todos los tipos de efecto (para los filtros)
     * @return array de TipoEfecto
     */
    public function getAllTiposEfectos(): array {
        try {
            return $this->efectoService->getAllTiposEfectos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
    
    /**
     * Obtiene el detalle completo de una receta (incluyendo ingredientes y efectos)
     * @param int $id Identificador único de la receta que buscamos
     * @return RecetaDetalle|null
     */
    public function getRecetaDetalle(int $id): ?RecetaDetalle {
        $receta = $this->recetaRepo->obtenerPorId($id);
        if (!$receta) return null;
        
        // Obtener ingredientes con cantidades (array asociativo de objeto Ingrediente con su cantidad)
        $ingredientes = $this->recetaRepo->obtenerIngredientesConCantidad($id);
        
        // Obtener efectos (array de objetos Efecto)
        $efectos = $this->recetaRepo->obtenerEfectosPorRecetaId($id);
        
        return new RecetaDetalle($receta, $ingredientes, $efectos);
    }

        /**
     * Devuelve array con las categorías de ingredientes y sus ingredientes
     * @return array ['setas' => [Ingrediente, ...], 'pescados_mariscos' => [...], ...]
     */
    public function sortIngredientesPorCategoria(): array {
        return $this->ingredienteService->sortIngredientesPorCategoria();
    }
}

?>