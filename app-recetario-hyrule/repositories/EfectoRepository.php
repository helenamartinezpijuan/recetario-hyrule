<?php 
namespace repositories;

use models\Efecto;
use models\TipoEfecto;
use Exception;

/**
 * La clase EfectoRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'efectos' de la base de datos
 */
class EfectoRepository extends BaseRepository {

    /**
     * Buscar todos los efectos
     * @param string $orden Columna de la tabla de la base de datos por la que ordenar el resultado de la consulta
     * @throws Exception Si hay error en la consultas
     * @return Efecto[] Array de objetos Efecto
     */
    public function obtenerTodos(string $orden = 'id_efecto'): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_efecto, id_tipo_efecto, descripcion FROM efectos ORDER BY $orden";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todos los efectos"); }

        // 4. OBTENER RESULTADOS
        $efectos = [];

        // 5. CREAR objeto Efecto
        while ($registro = $resultado->fetch_assoc()) {
            $efecto = new Efecto(
                $registro["id_efecto"],
                $registro["id_tipo_efecto"],
                $registro["descripcion"]
            );
            $efectos[] = $efecto;
        }

        // 7. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $efectos;
    }

    /**
     * Buscar efecto por su ID
     * @param int $id_efecto Identificador único del efecto
     * @throws Exception Si hay error en la consulta
     * @return Efecto Objeto Efecto con ID = $id_efecto
     */
    public function obtenerPorId(int $id_efecto): ?Efecto {
        // 1. VALIDAR parámetros de entrada
        if ($id_efecto <= 0) { throw new Exception("No se puede buscar un efecto sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_tipo_efecto, descripcion FROM efectos WHERE id_efecto=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de efecto por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_efecto);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de efecto por ID"); }

        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();

        // 8. VERIFICAR si hay resultados disponibles
        if ($resultado->num_rows === 0) {
            // No hay resultados disponibles
            $statement->close();
            $conn->close();
            return null;
        }

        // 9. OBTENER LA FILA como array asociativo
        $registro = $resultado->fetch_assoc();

        // 10. CREAR objeto Efecto
        $efecto = new Efecto(
            $id_efecto,
            $registro['id_tipo_efecto'],
            $registro['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $efecto;
    }

    /**
     * Buscar efectos por nombre
     * @param string $nombre Nombre del efecto introducido por el usuario
     * @throws Exception Si hay error en la consulta
     * @return Efecto[] Array de objetos Efecto
     */
    public function buscarPorNombre(string $nombre): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT efectos.id_efecto, efectos.id_tipo_efecto, efectos.descripcion, tipos_efectos.nombre 
            FROM efectos 
            INNER JOIN tipos_efectos USING(id_tipo_efecto) 
            WHERE tipos_efectos.nombre LIKE ? OR efectos.descripcion LIKE ?
            ORDER BY tipos_efectos.nombre;";
        
        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de efecto por nombre"); }
        
        // 4. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param('ss', $nombre, $nombre);
        
        // 5. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de efecto por nombre"); }
        
        // 6. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $efectos = [];
        
        // 7. CREAR objetos Efecto
        while ($registro = $resultado->fetch_assoc()) {
            $efecto = new Efecto(
                $registro["id_efecto"],
                $registro["id_tipo_efecto"],
                $registro["descripcion"]
            );
            $efectos[] = $efecto;
        }
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $efectos;
    }
}

?>