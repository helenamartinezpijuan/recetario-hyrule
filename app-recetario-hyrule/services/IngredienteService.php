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

    /**
     * Categorías de ingredientes y sus rangos de IDs en la BD
     */
    private const CATEGORIAS_INGREDIENTES = [
        'setas' => [
            'ids' => [37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 61, 62],
            'nombre_mostrar' => 'Setas'
        ],
        'pescados_mariscos' => [
            'ids' => [7, 8, 9, 10, 11, 12, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 83],
            'nombre_mostrar' => 'Pescados y Mariscos'
        ],
        'vegetales_flores_frutas' => [
            'ids' => [13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 60, 61, 62, 63, 64, 65, 88],
            'nombre_mostrar' => 'Vegetales, Flores y Frutas'
        ],
        'insectos_reptiles_monstruo' => [
            'ids' => [66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82],
            'nombre_mostrar' => 'Insectos, Reptiles y Partes de Monstruo'
        ],
        'varios' => [
            'ids' => [],  // Se llenará con los IDs no clasificados
            'nombre_mostrar' => 'Varios'
        ]
    ];

    private $ingredienteRepo;
    
    public function __construct() {
        $this->ingredienteRepo = new IngredienteRepository();
    }
        
    /**
     * Obtiene todos los ingredientes (para la vista principal)
     * @return Ingrediente[]
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
     * Busca ingredientes por nombre o descripción
     * @param string $nombre Nombre del ingrediente buscado
     * @return array de Ingrediente
     */
    public function buscarIngredientesPorNombre(string $nombre): array {
        try {
            if (empty(trim($nombre))) {
                return $this->getAllIngredientes();
            }
            return $this->ingredienteRepo->buscarPorNombre(trim($nombre));
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
        
    /**
     * Obtiene un ingrediente por su ID
     * @param int $id Identificador único del ingrediente
     * @return Ingrediente|null
     */
    public function getIngredientePorId(int $id): ?Ingrediente {
        try {
            return $this->ingredienteRepo->obtenerPorId($id);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return null;
        }
    }
        
    /**
     * Obtiene ingredientes filtrados por categorías de ingredientes y/o por IDs de localizaciones
     * @param array $ingredientes_ids IDs de todos los ingredientes de las categorias seleccionadas
     * @param array $localizaciones_ids
     * @return Ingrediente[]
     */
    public function getIngredientesFiltrados(array $ingredientes_idsv, array $localizaciones_ids): array {
        try {
            if (empty($ingredientes_ids) && empty($localizaciones_ids)) {
                return $this->getAllIngredientes();
            }
            return $this->ingredienteRepo->obtenerPorFiltros($ingredientes_ids, $localizaciones_ids);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Devuelve el array de categorías (para usar en las vistas)
     * @return array Las categorías con sus nombres para mostrar
     */
    public function getCategoriasDisponibles(): array {
        $categorias = [];
        foreach (self::CATEGORIAS_INGREDIENTES as $key => $categoria) {
            $categorias[$key] = $categoria['nombre_mostrar'];
        }
        return $categorias;
    }

    /**
     * Devuelve array con los IDs de ingredientes pertenecientes a las categorías indicadas
     * @param array $categorias Array de strings con las categorías a filtrar
     * @return array Array de IDs de ingredientes
     */
    public function getIngredientesPorCategoria($categorias): array {
        try {
            if (empty($categorias)) { return $this->getAllIngredientes(); }

            // 1. OBTENER todos los ingredientes
            $todosPorCategoria = $this->sortIngredientesPorCategoria();
            $ingredientes_ids = [];

            // 2. RECORRER las categorías seleccionadas y extraer los IDs
            foreach ($categorias as $categoria) {
                if (isset($todosPorCategoria[$categoria])) {
                    foreach ($todosPorCategoria[$categoria] as $ingrediente) {
                        $ingredientes_ids[] = $ingrediente->getIdIngrediente();
                    }
                }
            }
            
            return $ingredientes_ids;

        // 4. CONTROL DE ERRORES en caso de no poder conectar con el repositorio
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
        
    /**
     * Devuelve array con las categorías de ingredientes y sus ingredientes
     * @return array ['setas' => [Ingrediente, ...], 'pescados_mariscos' => [...], ...]
     */
    public function sortIngredientesPorCategoria(): array {
        try {
            // 1. OBTENER todos los ingredientes
            $todos = $this->ingredienteRepo->obtenerTodos();

            // 2. INICIALIZAR ARRAY con categorías
            $categorias = [];
            foreach (self::CATEGORIAS_INGREDIENTES as $key => $categoria) {
                $categorias[$key] = [];
            }

            // 3. AGRUPAR ingredientes por categorias
            foreach ($todos as $ingrediente) {
                $id = $ingrediente->getIdIngrediente();
                $categoriaAsignada = false;
                
                // 4. BUSCAR CATEGORIA donde está este ID
                foreach (self::CATEGORIAS_INGREDIENTES as $key => $categoria) {
                    if (in_array($id, $categoria['ids'])) {
                        $categorias[$key][] = $ingrediente;
                        $categoriaAsignada = true;
                        break;
                    }
                }
                
                // Si no está en ninguna categoría definida, va a 'varios'
                if (!$categoriaAsignada) {
                    $categorias['varios'][] = $ingrediente;
                }
            }
            return $categorias;

        // 5. CONTROL DE ERRORES en caso de no poder conectar con el repositorio
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
}

?>