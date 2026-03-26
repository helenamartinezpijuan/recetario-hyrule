<?php 

namespace repositories;

use models\Localizacion;
use Exception;

/**
 * La clase LocalizacionRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'localizaciones' de la base de datos
 */
class RecetaRepository extends BaseRepository {

    /**************************************************
     * BUSCAR sin filtros - Consulta SELECT sin WHERE *
     **************************************************/
    /**
     * Buscar localizaciones sin aplicar filtros
     * @param string $orden Columna de la tabla de la base de datos por la que ordenar el resultado de la consulta
     * @throws Exception Si hay error en la consultas
     * @return Localizacion[] Array de objetos Localizacion
     */
    public function obtenerTodos(string $orden = 'nombre'): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_localizacion, nombre, region, imagen, descripcion FROM localizaciones ORDER BY $orden";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todas las localizaciones"); }

        // 4. OBTENER RESULTADOS
        $localizaciones = [];

        // 5. CREAR objeto Localizacion
        while ($registro = $resultado->fetch_assoc()) {
            $localizacion = new Localizacion(
                $registro["id_localizacion"],
                $registro["nombre"],
                $registro["region"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $localizaciones[] = $localizacion;
        }

        // 7. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $localizaciones;
    }

    /****************************************************
     * BUSCAR por ID - Consulta SELECT con WHERE id=$id *
     ****************************************************/
    /**
     * Buscar localizacion por su ID
     * @param int $id_localizacion Identificador único de la localización
     * @throws Exception Si hay error en la consulta
     * @return Localizacion Objeto Localizacion con ID = $id_localizacion
     */
    public function obtenerPorId(int $id_localizacion): ?Localizacion {
        // 1. VALIDAR parámetros de entrada
        if ($id_localizacion <= 0) { throw new Exception("No se puede buscar una localización sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre, region, imagen, descripcion FROM localizaciones WHERE id_localizacion=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de localización por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_localizacion);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de localización por ID"); }

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

        // 10. CREAR objeto Localizacion
        $localizacion = new Localizacion(
            $id_localizacion,
            $fila['nombre'],
            $fila['region'],
            $fila['imagen'],
            $fila['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $localizacion;
    }

    /*************************************************
     * BUSCAR por nombre - Consulta SELECT con WHERE *
     *************************************************/
    /**
     * Busca localizaciones por nombre
     * @param string $nombre Nombre de la receta introducido por el usuario
     * @throws Exception Si hay error en la consulta
     * @return array Array de objetos Receta
     */
    /*public function buscarPorFiltros(string $nombre): array {
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
    }*/

    /**************************************************
     * BUSCAR con filtros - Consulta SELECT con WHERE *
     **************************************************/
    /**
     * Busca localizaciones aplicando filtros
     * @param array $filtros Array asociativo con filtros validados
     * @throws Exception Si hay error en la consulta
     * @return array Array de objetos Receta
     */
    public function obtenerPorFiltros(array $efectos_ids, array $ingredientes_ids): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT recetas.id_receta, recetas.nombre, recetas.imagen, recetas.descripcion
                    FROM recetas
                    WHERE 1=1";
        $tipos = "";
        $valores = [];

        // 3. AÑADIR FILTROS validados de los efectos
        if (!empty($efectos_ids)) {
            $placeholders = implode(',', array_fill(0, count($efectos_ids), '?'));
            $sql .= " AND EXISTS (SELECT 1 FROM recetas_efectos
                                WHERE recetas_efectos.id_receta = recetas.id_receta
                                AND recetas_efectos.id_efecto IN ($placeholders))";
            $tipos .= str_repeat('i', count($efectos_ids));
            $valores = array_merge($valores, $efectos_ids);
        }

        // 4. AÑADIR FILTROS validados de los ingredientes
         if (!empty($ingredientes_ids)) {
            $placeholders = implode(',', array_fill(0, count($ingredientes_ids), '?'));
            $sql .= " AND EXISTS (SELECT 1 FROM recetas_ingredientes 
                                WHERE recetas_ingredientes.id_receta = recetas.id_receta 
                                AND recetas_ingredientes.id_ingrediente IN ($placeholders))";
            $tipos .= str_repeat('i', count($ingredientes_ids));
            $valores = array_merge($valores, $ingredientes_ids);
        }
        
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

    public function obtenerIngredientesConCantidad(int $id_receta): array {
        // Obtener ingredientes con cantidades (array asociativo)
        return [];
    }

    public function obtenerEfectosPorRecetaId(int $id_receta): array {
        // Obtener efectos (array asociativo)
        return [];
    }

}

?>