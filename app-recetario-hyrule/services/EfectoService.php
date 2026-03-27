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
        try {
            return $this->efectoRepo->obtenerTodos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    // REVISAR ULTIMA CONSULTA A DEEPSEEK PARA LA REVISIÓN DE LOS SERVICES
    //He realizado los cambios que indicas, pero con algunas modificaciones:
    //MODIFICACION 1: En el EfectoService.php no he incluido la función getAllTiposEfectos porque no voy a realizar búsquedas filtradas para los efectos, solo por nombre (hay 11 en total, prefiero mostrar solo una lista y ofrecer la opción de búsqueda por la barra en caso de que alguien la quiera, pero la veo bastante innecesaria)
}

?>