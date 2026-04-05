<?php 
namespace services;

use models\Localizacion;
use repositories\LocalizacionRepository;
use helpers\Logger;
use Exception;

/**
 * La clase LocalizacionService se encarga de la validación y/o normalización de datos
 */
class LocalizacionService {
    private $localizacionRepo;
    
    public function __construct() {
        $this->localizacionRepo = new LocalizacionRepository();
    }
    
    /**
     * Obtiene todas las localizacions (para la vista principal)
     * @return Localizacion[]
     */
    public function getAllLocalizaciones(): array {
        try {
            return $this->localizacionRepo->obtenerTodos();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }  
    }

    /**
     * Busca localizaciones por nombre o región
     * @param string $nombre Nombre de la región o localización buscada
     * @return Localizacion[]
     */
    public function buscarLocalizacionesPorNombre(string $nombre): array {
        try {
            if (empty(trim($nombre))) {
                return $this->getAllLocalizaciones();
            }
            return $this->localizacionRepo->buscarPorNombre(trim($nombre));
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }
    
    /**
     * Obtiene una localización por su ID
     * @param int $id Identificador único de la localización
     * @return Localizacion|null
     */
    public function getLocalizacionPorId(int $id): ?Localizacion {
        try {
            return $this->localizacionRepo->obtenerPorId($id);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return null;
        }
    }
        
    /**
     * Obtiene localizaciones filtradas por nombre de región
     * @param array $regiones
     * @return Localizacion[]
     */
    public function getLocalizacionesFiltradas(array $regiones): array {
        try {
            if (empty($regiones)) {
                return $this->getAllLocalizaciones();
            }
            return $this->localizacionRepo->obtenerPorRegiones($regiones);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Obtiene todas las regiones (para los filtros)
     * @return array de strings para las regiones
     */
    public function getRegionesDisponibles(): array {
        try {
            return $this->localizacionRepo->obtenerRegiones();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Obtiene las localizaciones de una región concreta
     * @param string $region Nombre de la región
     * @return array Array de objetos Localizacion
     */
    public function getLocalizacionesPorRegionEspecifica(string $region): array {
        try {
            return $this->localizacionRepo->obtenerPorRegion($region);
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    }

    /**
     * Devuelve un array asociativo con las localizaciones agrupadas por región, definida por el ENUM de la tabla 'localizaciones'
     * @return array ['Hebra' => [Localizacion, ...], 'Eldin' => [...], ...]
     */
    public function getLocalizacionesPorRegion(): array {
        try {
            // 1. OBTENER todas las localizaciones
            $todos = $this->localizacionRepo->obtenerTodos();
            // 2. INICIALIZAR ARRAY de regiones vacías
            $regionesAgrupadas = [];

            // 3. AGRUPAR localizaciones por regiones
            foreach ($todos as $localizacion) {
                $region = $localizacion->getRegion();
                $regionesAgrupadas[$region][] = $localizacion;
            }
            return $regionesAgrupadas;

        // 4. CONTROL DE ERRORES en caso de no poder conectar con el repositorio
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            return [];
        }
    
    }
}

?>