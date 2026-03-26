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
     * @return array de Efecto
     */
    public function getAllEfectos(): array {
        return $this->efectoRepo->obtenerTodos();
    }
}

?>