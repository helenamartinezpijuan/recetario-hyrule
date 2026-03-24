<?php 
namespace services;

use helpers\Logger;
use Exception;

/**
 * La clase RecetaService se encarga de la validación y/o normalización de datos
 */
class RecetaService {
    private $dni_length = 9; // varchar(9)
    private $nombre_length = 50; // varchar(50)
    private $direccion_postal_length = 150; // varchar(150)
    private $num_cuenta_length = 24; // varchar(24)

    /**
     * Ruta base para archivos de datos
     */
    private const DATA_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'clientes' . DIRECTORY_SEPARATOR;

    public function prepararFiltrosBusqueda($ingredientes, $efectos): array {
        $filtros = [];
        if (!empty($nombre)) $filtros['nombre'] = '%' . $nombre . '%';
        if (!empty($direccion)) $filtros['direccion'] = '%' . $direccion . '%';
        if (!empty($numCuenta)) $filtros['num_cuenta'] = '%' . $numCuenta . '%';
        return $filtros;
    }

    /**
     * Validar todos los atributos del Cliente antes de insertarlo en la base de datos
     * @param string $dni DNI del cliente en formato y tamaño correctos
     * @param string $nombre Nombre del cliente (max. 50 caracteres)
     * @param string $direccion Dirección postal del cliente (max. 150 caracteres)
     * @param string $numCuenta Número de cuenta del cliente en formato y tamaño correctos
     * @return bool True si el cliente ha sido validado correctamente
     */
    public function validarCliente(string $dni, string $nombre, string $direccion, string $numCuenta): bool {
        if ( $this->validarDni($dni) == "" ) {
            //Logger::error("El DNI no tiene un formato correcto", __FILE__);
            return false;
        }
        if ( $this->validarNombre($nombre) == "" ) {
            return false;
        }
        if ( $this->validarDireccionPostal($direccion) == "" ) {
            return false;
        }
        if ( $this->validarNumCuenta($numCuenta) == "" ) {
            return false;
        }

        return true;
    }

    private function validarDni(string $dni): string {
        if ( strlen($dni) != $this->dni_length 
            || !ctype_digit(substr($dni, 0, 8)) 
            || !ctype_alpha( substr($dni, 8, 1)) ) {
            return "";
        } else {
            return strtoupper($dni);
        }
    }

    private function validarNombre(string $nombre): ?string {
        // Comprobar que el nombre no supere el límite de caracteres
        if ( strlen($nombre) > $this->nombre_length ) {
            return "";
        }
        else {
            // Crear un array con los nombres individuales
            $nombre_explode = explode(" ", $nombre);
            // Recorrer el array con cada nombre
            foreach ( $nombre_explode as $n ) {
                // Dejar cada string en minúsculas con la primera en mayúsculas
                ucfirst(strtolower($n));
            }
            // Obtener el string completo del nombre
            return implode(" ", $nombre_explode);
        }
    }

    // PENDIENTE REVISAR ESTAS DOS FUNCIONES PARA VALIDAR LA DIRECCION //
    private function validarDireccionPostal(string $direccion): ?string {
        if ( strlen($direccion) > $this->direccion_postal_length ) {
            return "";
        } else {
            return $direccion;
        }
    }

    /**
     * Construir la dirección postal completa a partir de los valores del formulario
     * @param string $via Tipo de vía seleccionado de un desplegable fijo
     * @param string $direccion Nombre de la calle y número introducidos en el formulario
     * @param string $municipio Municipio de la dirección
     * @return string Devuelve la dirección completa en el formato Via Direccion (Municipio)
     */
    public function construirDireccion(string $via, string $direccion, string $municipio): string {
        // El tipo de via y el municipio se escriben en minúsculas y la primera en mayúsculas
        $tipo_via = ucfirst(strtolower($via));
        $municipio = ucfirst(strtolower($municipio));
        // La cadena final tendrá el formato: Via Direccion (Municipio)
        $direccion_postal = trim($tipo_via) . ' ' . $direccion . ' (' . $municipio . ')';
        // Validar que la cadena resultante no supere los 150 caracteres
        if ( strlen($direccion_postal) > 150 ) {
            return "";
        } else {
            return $direccion_postal;
        }
    }

    /**
     * Validar el número de cuenta que el usuario introduce en el formulario
     * @param string $num_cuenta Número de cuenta del usuario sin las letras iniciales (ES)
     * @throws Exception Si el número de cuenta no tiene 24 caracteres
     * @return string Devuelve un string con el número de cuenta en formato ES00 0000 0000 0000 0000 0000
     */
    public function validarNumCuenta (string $num_cuenta): ?string {
        // Normalizamos el formato del número de cuenta
        $num_cuenta_formateado = 'ES' . str_replace(' ', '', $num_cuenta); // ES0000000000000000000000
        // Si el resultado no es una cadena de 24 caracteres, lanzamos una excepción
        if ( strlen($num_cuenta_formateado) != 24 ) { throw new Exception("El número de cuenta no es válido"); }

        // Dividimos la cadena en bloques de 4 caracteres
        $bloques = str_split($num_cuenta, 4);
        // Unimos con espacios para mostrar el formato ES00 0000 0000 0000 0000 0000
        $num_cuenta_formateado = implode(' ', $bloques);

        return $num_cuenta_formateado;
    }

    public function normalizarDatosBusqueda(array $datos): array {
        return array();
    }


    /**
     * Método auxiliar para obtener los tipos de vía desde un archivo .json
     * @return array Lista de tipos de vía
     */
    public function obtenerTiposVia(): array {
        static $tipos_vias = null;
        
        if ($tipos_vias === null) {
            $archivo = self::DATA_PATH . 'tipos_vias.json';
            
            // DEBUG: Podemos ver la ruta exacta
            error_log("Buscando archivo en: " . $archivo);
            
            if (file_exists($archivo)) {
                $contenido = file_get_contents($archivo);
                $tipos_vias = json_decode($contenido, true);
                
                // Verificar si el JSON es válido
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Logger::error("Error decodificando JSON: " . json_last_error_msg(), __FILE__);
                    $tipos_vias = ['Calle', 'Avenida', 'Plaza', 'Paseo', 'Ronda'];
                }
            } else {
                Logger::error("Archivo de tipos de vía no encontrado: $archivo", __FILE__);
                $tipos_vias = ['Calle', 'Avenida', 'Plaza', 'Paseo', 'Ronda'];
            }
        }
        
        return $tipos_vias;
    }
}

?>