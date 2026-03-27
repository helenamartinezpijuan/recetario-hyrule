<?php 
namespace services;

use models\Ingrediente;
use repositories\IngredienteRepository;
use helpers\Logger;
use Exception;

/**
 * La clase IngredienteService se encarga de la validación y/o normalización de datos
 */
class IngredienteService {
    private $ingredienteRepo;
    
    public function __construct() {
        $this->ingredienteRepo = new IngredienteRepository();
    }
    
    /**
     * Devuelve array con las categorías de ingredientes y sus ingredientes
     * @return array ['setas' => [Ingrediente, ...], 'pescados_mariscos' => [...], ...]
     */
    public function getIngredientesPorCategoria(): array {
        try {
            // 1. OBTENER todos los ingredientes
            $todos = $this->ingredienteRepo->obtenerTodos();

            // 2. INICIALIZAR ARRAY con categorías manualmente creadas
            $categorias = [
                'setas' => [],
                'pescados_mariscos' => [],
                'vegetales_flores_frutas' => [],
                'insectos_reptiles_monstruo' => [],
                'varios' => []
            ];

            // 3. AGRUPAR ingredientes por categorias
            foreach ($todos as $ingrediente) {
                $id = $ingrediente->getIdIngrediente();
                
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

        // 4. CONTROL DE ERRORES en caso de no poder conectar con el repositorio
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
    
    /**
     * Obtiene todos los ingredientes (para la vista principal)
     * @return array de Ingrediente
     */
    public function getAllIngredientes(): array {
        try {
            return $this->ingredienteRepo->obtenerTodos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }  
    }
    
    /**
     * Obtiene ingredientes filtrados por categorías de ingredientes y/o por localizaciones
     * @param array $ingredientes_ids
     * @param array $localizaciones_ids
     * @return array de objetos Ingrediente
     */
    public function getIngredientesFiltrados(array $ingredientes_ids, array $localizaciones_ids): array {
        try {
            return $this->ingredienteRepo->obtenerPorFiltros($ingredientes_ids, $localizaciones_ids);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
}

?>