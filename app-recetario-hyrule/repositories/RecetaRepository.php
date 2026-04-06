<?php 

namespace repositories;

use models\Receta;
use models\Efecto;
use models\TipoEfecto;
use models\Ingrediente;
use Exception;

/**
 * La clase RecetaRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'recetas' de la base de datos
 */
class RecetaRepository extends BaseRepository {
    /**
     * Buscar recetas sin aplicar filtros
     * @throws Exception Si hay error en la consultas
     * @return Receta[]
     */
    public function obtenerTodos(): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_receta, nombre, imagen, descripcion FROM recetas ORDER BY nombre";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todas las recetas"); }

        // 4. OBTENER RESULTADOS
        $recetas = [];

        // 5. CREAR objeto Receta
        while ($registro = $resultado->fetch_assoc()) {
            $receta = new Receta(
                $registro["id_receta"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $recetas[] = $receta;
        }

        // 6. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $recetas;
    }

    /**
     * Buscar receta por su ID
     * @param int $id_receta Identificador único de la receta
     * @throws Exception Si hay error en la consulta
     * @return Receta|null
     */
    public function obtenerPorId(int $id_receta): ?Receta {
        // 1. VALIDAR parámetros de entrada
        if ($id_receta <= 0) { throw new Exception("No se puede buscar una receta sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre, imagen, descripcion FROM recetas WHERE id_receta=?";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_receta);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de receta por ID"); }

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

        // 10. CREAR objeto Receta
        $receta = new Receta(
            $id_receta,
            $registro['nombre'],
            $registro['imagen'],
            $registro['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $receta;
    }

    /**
     * Busca recetas por nombre
     * @param string $nombre Texto introducido en la barra buscadora de la receta
     * @throws Exception Si hay error en la consulta
     * @return Receta[]
     */
    public function buscarPorNombre(string $nombre): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $like_nombre = "%$nombre%";
        $sql = "SELECT id_receta, nombre, imagen, descripcion 
            FROM recetas 
            WHERE nombre LIKE ? OR descripcion LIKE ? 
            ORDER BY nombre";

        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de receta por nombre"); }
        
        // 4. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param('ss', $like_nombre, $like_nombre);
        
        // 5. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de receta por nombre"); }
        
        // 6. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $recetas = [];
        
        // 7. CREAR objetos Receta
        while ($registro = $resultado->fetch_assoc()) {
            $receta = new Receta(
                $registro["id_receta"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $recetas[] = $receta;
        }
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $recetas;
    }

    /**
     * Busca recetas aplicando filtros
     * @param array $efectos_ids Array de IDs de efectos
     * @param array $ingredientes_ids Array de IDs de ingredientes
     * @return Receta[]
     */
    public function obtenerPorFiltros(array $efectos_ids, array $ingredientes_ids): array {
        // 1. VALIDAR parámetros de entrada
        if (empty($efectos_ids) && empty($ingredientes_ids)) { return []; }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT DISTINCT recetas.id_receta, recetas.nombre, recetas.imagen, recetas.descripcion 
                    FROM recetas 
                    WHERE 1=1";
        $tipos = "";
        $valores = [];

        // 4. AÑADIR FILTROS de los efectos
        if (!empty($efectos_ids)) {
            $placeholders = implode(',', array_fill(0, count($efectos_ids), '?'));
            $sql .= " AND EXISTS (SELECT 1 FROM recetas_efectos 
                                WHERE recetas_efectos.id_receta = recetas.id_receta 
                                AND recetas_efectos.id_efecto IN ($placeholders))";
            $tipos .= str_repeat('i', count($efectos_ids));
            $valores = array_merge($valores, $efectos_ids);
        }

        // 5. AÑADIR FILTROS de los ingredientes
         if (!empty($ingredientes_ids)) {
            $placeholders = implode(',', array_fill(0, count($ingredientes_ids), '?'));
            $sql .= " AND EXISTS (SELECT 1 FROM recetas_ingredientes 
                                WHERE recetas_ingredientes.id_receta = recetas.id_receta 
                                AND recetas_ingredientes.id_ingrediente IN ($placeholders))";
            $tipos .= str_repeat('i', count($ingredientes_ids));
            $valores = array_merge($valores, $ingredientes_ids);
        }
        
        $sql .= " ORDER BY recetas.nombre";
        
        // 6. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por filtros"); }
        
        // 7. VINCULAR PARÁMETROS si hay filtros
        if (!empty($valores)) {
            // Uso de operador splat (...) para evitar un switch(count($valores))
            $statement->bind_param($tipos, ...$valores);
        }
        
        // 8. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por filtros"); }
        
        // 9. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $recetas = [];
        
        // 10. CREAR objetos Receta
        while ($registro = $resultado->fetch_assoc()) {
            $receta = new Receta(
                $registro["id_receta"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $recetas[] = $receta;
        }
        
        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $recetas;
    }

    /**
     * Obtener ingredientes con cantidades (array asociativo)
     * @param int $id_receta ID de la receta
     * @throws Exception Si hay error en la consulta
     * @return array{ingrediente: Ingrediente, cantidad: int}
     */
    public function obtenerIngredientesConCantidad(int $id_receta): array {
        // 1. VALIDAR parámetros de entrada
        if ($id_receta <= 0) { throw new Exception("No se puede buscar una receta sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT ingredientes.id_ingrediente, ingredientes.nombre, ingredientes.imagen, ingredientes.descripcion, recetas_ingredientes.cantidad 
                FROM recetas_ingredientes 
                INNER JOIN ingredientes USING(id_ingrediente) 
                WHERE recetas_ingredientes.id_receta = ?";
                //ORDER BY ingredientes.nombre";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando obtención de ingredientes de receta"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_receta);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando obtención de ingredientes de receta"); }

        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $ingredientes = [];

        // 8. CREAR array asociativo
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro['id_ingrediente'],
                $registro['nombre'],
                $registro['imagen'],
                $registro['descripcion']
            );
            $ingredientes[] = [
                'ingrediente' => $ingrediente,
                'cantidad' => $registro['cantidad']
            ];
        }

        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $ingredientes;
    }

    /**
     * Obtener efectos de una receta
     * @param int $id_receta ID de la receta
     * @throws Exception Si hay error en la consulta
     * @return Efecto[]
     */
    public function obtenerEfectosPorRecetaId(int $id_receta): array {
        // 1. VALIDAR parámetros de entrada
        if ($id_receta <= 0) { throw new Exception("No se puede buscar una receta sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT efectos.id_efecto, efectos.id_tipo_efecto, efectos.imagen, efectos.descripcion, tipos_efectos.nombre 
                FROM recetas_efectos 
                INNER JOIN efectos USING(id_efecto) 
                INNER JOIN tipos_efectos USING(id_tipo_efecto) 
                WHERE recetas_efectos.id_receta = ? 
                ORDER BY tipos_efectos.nombre";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando obtención de ingredientes de receta"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_receta);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando obtención de ingredientes de receta"); }

        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $efectos = [];

        // 8. CREAR objetos Efecto
        while ($registro = $resultado->fetch_assoc()) {
            $tipoEfecto = new TipoEfecto(
                $registro['id_tipo_efecto'],
                $registro['nombre']
            );
            $efecto = new Efecto(
                $registro['id_efecto'],
                $tipoEfecto,
                $registro['imagen'],
                $registro['descripcion']
            );
            $efectos[] = $efecto;
        }

        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $efectos;
    }

    /**
     * Crear nuevo registro de receta en el inventario del usuario
     * @param Receta $receta Objeto Receta a insertar en la base de datos
     * @throws Exception Si hay error en la consulta
     * @return Receta
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