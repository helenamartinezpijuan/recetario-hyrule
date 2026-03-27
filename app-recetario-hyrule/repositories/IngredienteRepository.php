<?php 
namespace repositories;

use models\Ingrediente;
use Exception;

/**
 * La clase IngredienteRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'ingredientes' de la base de datos
 */
class IngredienteRepository extends BaseRepository {

    /**
     * Buscar todos los ingredientes (sin filtros)
     * @param string $orden Columna de la tabla de la base de datos por la que ordenar el resultado de la consulta
     * @throws Exception Si hay error en la consultas
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function obtenerTodos(string $orden = 'nombre'): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_ingrediente, nombre, imagen, descripcion FROM ingredientes ORDER BY $orden";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todos los ingredientes"); }

        // 4. OBTENER RESULTADOS
        $ingredientes = [];

        // 5. CREAR objeto Ingrediente
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro["id_ingrediente"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $ingredientes[] = $ingrediente;
        }

        // 7. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $ingredientes;
    }

    /**
     * Buscar ingrediente por su ID
     * @param int $id_ingrediente Identificador único de la localización
     * @throws Exception Si hay error en la consulta
     * @return Ingrediente Objeto Ingrediente con ID = $id_ingrediente
     */
    public function obtenerPorId(int $id_ingrediente): ?Ingrediente {
        // 1. VALIDAR parámetros de entrada
        if ($id_ingrediente <= 0) { throw new Exception("No se puede buscar una localización sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre, region, imagen, descripcion FROM ingredientes WHERE id_ingrediente=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de localización por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_ingrediente);

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

        // 10. CREAR objeto Ingrediente
        $ingrediente = new Ingrediente(
            $id_ingrediente,
            $fila['nombre'],
            $fila['region'],
            $fila['imagen'],
            $fila['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $ingrediente;
    }

    /**
     * Buscar ingredientes por nombre
     * @param string $nombre Nombre de la receta introducido por el usuario
     * @throws Exception Si hay error en la consulta
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function buscarPorNombre(string $nombre): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_ingrediente, nombre, region, imagen, descripcion 
            FROM ingredientes 
            WHERE nombre LIKE ? OR region LIKE ?
            ORDER BY nombre;";
        
        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de localización por nombre"); }
        
        // 4. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param('ss', $nombre, $nombre);
        
        // 5. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de localización por nombre"); }
        
        // 6. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $ingredientes = [];
        
        // 7. CREAR objetos Ingrediente
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro["id_ingrediente"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $ingredientes[] = $ingrediente;
        }
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $ingredientes;
    }

    /**
     * Obtener todas las regiones disponibles
     * @return array Array de strings con los nombres de las regiones
     */
    public function obtenerRegiones(): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT DISTINCT region FROM ingredientes ORDER BY region;";

        // 3. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todas las regiones"); }

        // 4. OBTENER RESULTADOS
        $regiones = [];
        while ($registro = $resultado->fetch_assoc()) {
            $regiones[] = $registro["region"];
        }

        // 5. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $regiones;
    }

    /**
     * Obtener todas las ingredientes de una región específica
     * @param string $region Región de la que obtener sus ingredientes
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function obtenerPorRegion(string $region): array {
        // 1. VALIDAR parámetro de entrada

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_ingrediente, nombre, imagen, descripcion FROM ingredientes WHERE region=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de ingredientes por región"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("s", $region);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de ingredientes por región"); }

        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $ingredientes = [];

        // 8. CREAR array de objetos Ingrediente
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro["id_ingrediente"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $ingredientes[] = $ingrediente;
        }

        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $ingredientes;
    }

    /**
     * Obtener todas las ingredientes de varias regiones
     * @param array $regiones Regiones de las que obtener sus ingredientes
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function obtenerPorRegiones(array $regiones): array {
        // 1. VALIDAR parámetros de entrada
        if (empty($regiones)) { return []; }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_ingrediente, nombre, imagen, descripcion 
            FROM ingredientes 
            WHERE 1=1";
        $tipos = "";
        $valores = [];

        // 4. AÑADIR FILTROS de regiones
        if (!empty($regiones)) {
            $placeholders = implode(',', array_fill(0, count($regiones), '?'));
            $sql .= " AND region IN ($placeholders)";
            $tipos .= str_repeat('s', count($regiones));
            $valores = array_merge($valores, $regiones);
        }
        $sql .= " ORDER BY nombre;";
        
        // 5. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por regiones"); }
        
        // 6. VINCULAR PARÁMETROS si hay filtros
        if (!empty($valores)) {
            // Uso de operador splat (...) para evitar un switch(count($valores))
            $statement->bind_param($tipos, ...$valores);
        }
        
        // 7. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por regiones"); }
        
        // 8. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $ingredientes = [];
        
        // 9. CREAR objetos Ingrediente
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro["id_ingrediente"],
                $registro["nombre"],
                $registro["imagen"],
                $registro["descripcion"]
            );
            $ingredientes[] = $ingrediente;
        }
        
        // 10. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $ingredientes;
    }

}

?>