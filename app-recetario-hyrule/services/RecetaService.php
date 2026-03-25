<?php 
namespace services;

use models\RecetaDetalle;
use repositories\RecetaRepository;
use repositories\IngredienteRepository;
use repositories\EfectoRepository;
use helpers\Logger;
use Exception;

/**
 * La clase RecetaService se encarga de la validación y/o normalización de datos
 */
class RecetaService {
    private $recetaRepo;
    private $ingredienteRepo;
    private $efectoRepo;
    
    public function __construct() {
        $this->recetaRepo = new RecetaRepository();
        $this->ingredienteRepo = new IngredienteRepository();
        $this->efectoRepo = new EfectoRepository();
    }
    
    /**
     * Devuelve array con las categorías de ingredientes y sus ingredientes
     * @return array ['setas' => [Ingrediente, ...], 'pescados_mariscos' => [...], ...]
     */
    public function getIngredientesPorCategoria(): array {

        $todos = $this->ingredienteRepo->obtenerTodos(); // PENDIENTE IMPLEMENTAR MÉTODO EN IngredienteRepository
        
        $categorias = [
            'setas' => [],
            'pescados_mariscos' => [],
            'vegetales_flores_frutas' => [],
            'insectos_reptiles_monstruo' => [],
            'varios' => []
        ];
        
        foreach ($todos as $ingrediente) {
            $id = $ingrediente->getIdIngrediente();
            $nombre = $ingrediente->getNombre(); // DE MOMENTO NO BUSCAMOS POR NOMBRE SINO POR ID
            
            // Clasificación según IDs en la base de datos
            if (($id >= 37 && $id <= 46) || $id == 61 || $id == 62) {
                $categorias['setas'][] = $ingrediente;
            } 
            elseif (($id >= 7 && $id <= 12) || ($id >= 47 && $id <= 59) || $id == 83) {
                $categorias['pescados_mariscos'][] = $ingrediente;
            }
            elseif (($id >= 13 && $id <= 33) || ($id >= 60 && $id <= 65) || $id == 88) {
                $categorias['vegetales_flores_frutas'][] = $ingrediente;
            }
            elseif (($id >= 66 && $id <= 82)) {
                $categorias['insectos_reptiles_monstruo'][] = $ingrediente;
            }
            else {
                $categorias['varios'][] = $ingrediente;
            }
        }
        return $categorias;
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
        return $this->recetaRepo->buscarPorFiltros($efectos_ids, $ingredientes_ids);
    }
    
    /**
     * Obtiene el detalle completo de una receta (incluyendo ingredientes y efectos)
     * @param int $id Identificador único de la receta que buscamos
     * @return RecetaDetalle|null
     */
    public function getRecetaDetalle(int $id): ?RecetaDetalle {
        $receta = $this->recetaRepo->buscarPorId($id);
        if (!$receta) return null;
        
        // Obtener ingredientes con cantidades (array asociativo)
        $ingredientes = $this->recetaRepo->obtenerIngredientesConCantidad($id);
        
        // Obtener efectos (array asociativo)
        $efectos = $this->recetaRepo->obtenerEfectosPorRecetaId($id);
        
        return new RecetaDetalle($receta, $ingredientes, $efectos);
    }
}

?>