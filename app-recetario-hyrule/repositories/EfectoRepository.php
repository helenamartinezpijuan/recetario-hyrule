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

        // 5. CREAR objeto Efecto con su TipoEfecto correspondiente
        while ($registro = $resultado->fetch_assoc()) {
            $tipo_efecto = $this->obtenerTipoEfectoPorId($registro["id_tipo_efecto"]);

            $efecto = new Efecto(
                $registro["id_efecto"],
                $tipo_efecto,
                $registro["descripcion"]
            );
            $efectos[] = $efecto;
        }

        // 6. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $efectos;
    }

    /**
     * Buscar efecto por su ID
     * @param int $id_efecto Identificador único del efecto
     * @throws Exception Si hay error en la consulta
     * @return Efecto|null Objeto Efecto con ID = $id_efecto
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

        // 10. CREAR objeto Efecto con su TipoEfecto correspondiente
        $tipo_efecto = $this->obtenerTipoEfectoPorId($registro['id_tipo_efecto']);

        $efecto = new Efecto(
            $id_efecto,
            $tipo_efecto,
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
        $likeNombre = "%$nombre%";
        $sql = "SELECT efectos.id_efecto, efectos.id_tipo_efecto, efectos.descripcion, tipos_efectos.nombre 
            FROM efectos 
            INNER JOIN tipos_efectos USING(id_tipo_efecto) 
            WHERE tipos_efectos.nombre LIKE ? OR efectos.descripcion LIKE ?
            ORDER BY tipos_efectos.nombre;";
        
        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de efecto por nombre"); }
        
        // 4. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param('ss', $likeNombre, $likeNombre);
        
        // 5. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de efecto por nombre"); }
        
        // 6. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $efectos = [];
        
        // 7. CREAR objetos Efecto con sus TipoEfecto
        while ($registro = $resultado->fetch_assoc()) {
            $tipoEfecto = new TipoEfecto(
                $registro["id_tipo_efecto"],
                $registro["tipo_nombre"]
            );

            $efecto = new Efecto(
                $registro["id_efecto"],
                $tipoEfecto,
                $registro["descripcion"]
            );
            $efectos[] = $efecto;
        }
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $efectos;
    }

    /**
     * Obtener todos los tipos de efecto
     * @return TipoEfecto[] Array de objetos TipoEfecto
     */
    public function obtenerTipos(): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_tipo_efecto, nombre FROM tipos_efectos ORDER BY nombre";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todos los tipos de efectos"); }

        // 4. OBTENER RESULTADOS
        $tipos_efectos = [];

        // 5. CREAR objeto TipoEfecto
        while ($registro = $resultado->fetch_assoc()) {
            $tipo_efecto = new TipoEfecto(
                $registro["id_tipo_efecto"],
                $registro["nombre"]
            );
            $tipos_efectos[] = $tipo_efecto;
        }

        // 6. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $tipos_efectos;
    }

    /**
     * Obtener un TipoEfecto por su ID
     * @param int $id_tipo_efecto
     * @throws Exception Si hay un error en la consulta
     * @return TipoEfecto|null Objeto TipoEffecto con ID = $id_tipo_efecto
     */
    public function obtenerTipoEfectoPorId(int $id_tipo_efecto): ?TipoEfecto {
        // 1. VALIDAR parámetros de entrada
        if ($id_tipo_efecto <= 0) { throw new Exception("No se puede buscar un tipo de efecto sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre FROM tipos_efectos WHERE id_tipo_efecto=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de tipo de efecto por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_tipo_efecto);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de tipo de efecto por ID"); }

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

        // 10. CREAR objeto TipoEfecto
        $tipo_efecto = new TipoEfecto(
            $id_tipo_efecto,
            $registro['nombre']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $tipo_efecto;
    }
}

?>