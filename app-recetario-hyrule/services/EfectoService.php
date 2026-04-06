<?php 
namespace services;

use models\Efecto;
use repositories\EfectoRepository;
use helpers\Logger;
use Exception;

/**
 * La clase EfectoService se encarga de la validación y/o normalización de datos
 */
class EfectoService {
    private $efectoRepo;
    
    public function __construct() {
        $this->efectoRepo = new EfectoRepository();
    }
    
    /**
     * Obtiene todos los efectos (para la vista principal)
     * @return Efecto[]
     */
    public function getAllEfectos(): array {
        try {
            return $this->efectoRepo->obtenerTodos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
        
    /**
     * Busca efectos por nombre del tipo de efecto
     * @param string $nombre Nombre del efecto buscado
     * @return Efecto[]
     */
    public function buscarEfectosPorNombre(string $nombre): array {
        try {
            if (empty(trim($nombre))) {
                return $this->getAllEfectos();
            }
            return $this->efectoRepo->buscarPorNombre(trim($nombre));
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
        
    /**
     * Obtiene un efecto por su ID
     * @param int $id Identificador único del efecto
     * @return Efecto|null
     */
    public function getEfectoPorId(int $id): ?Efecto {
        try {
            return $this->efectoRepo->obtenerPorId($id);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return null;
        }
    }
    
    /**
     * Obtiene todos los tipos de efecto (para filtros)
     * @return array de TipoEfecto
     */
    public function getAllTiposEfectos(): array {
        try {
            return $this->efectoRepo->obtenerTipos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

}

?>