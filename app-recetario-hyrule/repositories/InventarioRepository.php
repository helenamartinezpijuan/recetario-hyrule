<?php 

namespace repositories;

use models\Inventario;
use Exception;

/**
 * La clase RecetaRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'recetas' de la base de datos. Por norma general, las funciones siguen el siguiente orden lógico:
 *  1. VALIDAR los parámetros de la función
 *  2. OBTENER CONEXIÓN a la base de datos
 *  3. CONSTRUIR CONSULTA SQL
 *  4. PREPARAR CONSULTA parametrizada
 *  5. VINCULAR PARÁMETROS a la consulta parametrizada
 *  6. EJECUTAR CONSULTA con manejo de errores
 *  7. OBTENER RESULTADOS o VERIFICAR MODIFICACIÓN
 *  8. CREAR nuevo objeto (o array de objetos) Receta
 *  9. LIMPIEZA de la conexión
 */
class InventarioRepository extends BaseRepository {

    /**
     * El array COLUMNAS_PERMITIDAS especifica las columnas existentes en la tabla 'recetas' de la base de datos
     * @var array Strings con los nombres de las columnas de la tabla 'recetas'
     */
    public const COLUMNAS_PERMITIDAS = [
        'id_receta', 
        'nombre', 
        'imagen', 
        'descripcion'
    ];


    /**************************************
     * BUSCAR - Consulta SELECT sin WHERE *
     **************************************/
    /**
     * Buscar recetas sin aplicar filtros
     * @param string $orden Columna de la tabla de la base de datos por la que ordenar el resultado de la consulta
     * @throws Exception Si hay error en la consultas
     * @return Receta[] Array de objetos Receta
     */
    public function obtenerTodos(string $orden = 'nombre'): array {
        // 1. VALIDAR parámetros de entrada
        if (!in_array($orden, self::COLUMNAS_PERMITIDAS)) {
            $orden = 'nombre'; // Valor por defecto
        }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_receta, nombre, imagen, descripcion FROM recetas ORDER BY $orden";

        // 4. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todas las recetas"); }

        // 5. OBTENER RESULTADOS
        $recetas = [];

        // 6. CREAR objeto Receta
        while ($registro = $resultado->fetch_assoc()) {
            $receta = new Receta(
                $registro["id_receta"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $recetas[] = $receta;
        }

        // 7. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $recetas;
    }

    /*********************************************
     * BUSCAR - Consulta SELECT con WHERE id=$id *
     *********************************************/
    /**
     * Buscar receta por su ID
     * @param int $id_receta Identificador único de la receta
     * @throws Exception Si hay error en la consulta
     * @return Receta Objeto Receta con ID = $id_receta
     */
    public function buscarPorId(int $id_receta): ?Receta {
        // 1. VALIDAR parámetros de entrada
        if ($id_receta <= 0) { throw new Exception("No se puede buscar una receta sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre, imagen, descripcion FROM recetas WHERE id_receta=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_receta);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por ID"); }

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
        $fila = $resultado->fetch_assoc();

        // 10. CREAR objeto cliente
        $receta = new Receta(
            $id_receta,
            $fila['nombre'],
            $fila['imagen'],
            $fila['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $receta;
    }

    /**************************************
     * BUSCAR - Consulta SELECT con WHERE *
     **************************************/
    /**
     * Busca recetas aplicando filtros
     * @param array $filtros Array asociativo con filtros validados
     * @throws Exception Si hay error en la consulta
     * @return array Array de objetos Receta
     */
    public function buscarPorFiltros(array $filtros): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_receta, nombre, imagen, descripcion FROM recetas WHERE 1=1";
        $tipos = "";
        $valores = [];

        // 3. AÑADIR FILTROS validados
        foreach ($filtros as $columna => $valor) { 
            // Validar que la columna existe en la tabla
            if (in_array($columna, self::COLUMNAS_PERMITIDAS)) {
                $sql .= " AND $columna LIKE ?";
                $tipos .= "s";
                $valores[] = $valor;
            }
        }
        $sql .= " ORDER BY nombre";
        
        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por filtros"); }
        
        // 5. VINCULAR PARÁMETROS si hay filtros
        if (!empty($valores)) {
            // Uso de operador splat (...) para evitar un switch(count($valores))
            $statement->bind_param($tipos, ...$valores);
        }
        
        // 6. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por filtros"); }
        
        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $recetas = [];
        
        // 8. CREAR objetos Receta
        while ($registro = $resultado->fetch_assoc()) {
            $receta = new Receta(
                $registro["id_receta"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $recetas[] = $receta;
        }
        
        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $recetas;
    }

    /********************************
     * CREAR - Consulta INSERT INTO *
     ********************************/
    /**
     * Crear nuevo registro de receta en el inventario del usuario
     * @param Receta $receta Objeto Receta a insertar en la base de datos
     * @throws Exception Si hay error en la consulta
     * @return Receta Objeto Receta con el id_receta
     */
    public function crearReceta(Receta $receta): ?Receta {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "INSERT INTO recetas (nombre, imagen, descripcion) VALUES (?, ?, ?)";

        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando inserción de receta en el inventario del usuario"); }

        // 4. EXTRAER VALORES del objeto Receta (necesario para bind_param no lance un warning porque necesita una referencia a la variable original, no solo el valor de retorno de una función)
        $nombre = $receta->getNombre();
        $imagen = $receta->getImagen();
        $descripcion = $receta->getDescripcion();

        // 5. VINCULAR PARÁMETROS obtenidos del objeto Receta a la consulta parametrizada
        $statement->bind_param("sss", $nombre, $imagen, $descripcion);

        // 6. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando inserción de receta en el inventario del usuario"); }

        // 7. OBTENER el ID de la receta en el inventario del usuario generado por la base de datos
        $id_generado = $statement->insert_id;
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();

        return new Receta($id_generado, $nombre, $imagen, $descripcion);
    }

    /******************************
     * ELIMINAR - Consulta DELETE *
     ******************************/
    /**
     * Eliminar registro de receta en el inventario del usuario
     * @param int $id_receta Identificador único de la receta en el inventario
     * @throws Exception Si hay error en la consulta
     * @return bool True si la eliminación se realiza con éxito
     */
    public function eliminarPorId(int $id_receta): bool {
        // 1. VALIDAR parámetros de entrada
        if ($id_receta <= 0) { throw new Exception("No se puede eliminar una receta sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "DELETE FROM recetas WHERE id_receta=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando eliminación"); }

        // 5. EJECUTAR CONSULTA con manejo de errores
        $statement->bind_param("i", $id_receta);
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando eliminación"); }

        // 6. VERIFICAR si la actualización ha modificado algún dato
        $eliminado = $statement->affected_rows > 0;

        // 7. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $eliminado;
    }

}

?>