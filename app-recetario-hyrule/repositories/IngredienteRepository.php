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
     * @param int $id_ingrediente Identificador único del ingrediente
     * @throws Exception Si hay error en la consulta
     * @return Ingrediente Objeto Ingrediente con ID = $id_ingrediente
     */
    public function obtenerPorId(int $id_ingrediente): ?Ingrediente {
        // 1. VALIDAR parámetros de entrada
        if ($id_ingrediente <= 0) { throw new Exception("No se puede buscar un ingrediente sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT nombre, imagen, descripcion FROM ingredientes WHERE id_ingrediente=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de ingrediente por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_ingrediente);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de ingrediente por ID"); }

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

        // 10. CREAR objeto Ingrediente
        $ingrediente = new Ingrediente(
            $id_ingrediente,
            $registro['nombre'],
            $registro['imagen'],
            $registro['descripcion']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $ingrediente;
    }

    /**
     * Buscar ingredientes por nombre
     * @param string $nombre Nombre del ingrediente introducido por el usuario
     * @throws Exception Si hay error en la consulta
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function buscarPorNombre(string $nombre): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_ingrediente, nombre, imagen, descripcion 
            FROM ingredientes 
            WHERE nombre LIKE ? OR descripcion LIKE ?
            ORDER BY nombre;";
        
        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de ingrediente por nombre"); }
        
        // 4. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param('ss', $nombre, $nombre);
        
        // 5. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de ingrediente por nombre"); }
        
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
     * Buscar ingredientes aplicando filtros
     * @param array $categorias_ingrediente Array asociativo de ingredientes por categorías
     * @param array $localizaciones_ids Array de ids de las localizaciones
     * @return Ingrediente[] Array de objetos Ingrediente
     */
    public function obtenerPorFiltros(array $categorias_ingrediente, array $localizaciones_ids): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT ingredientes.id_ingrediente, ingredientes.nombre, ingredientes.imagen, ingredientes.descripcion
                    FROM ingredientes
                    WHERE 1=1";
        $tipos = "";
        $valores = [];

        // 3. AÑADIR FILTROS validados de los efectos
        if (!empty($categorias_ingrediente)) {
            // EL ARRAY CATEGORIAS_INGREDIENTE YA VIENE EN FORMATO ingrediente -> categoria (no haría falta conectar con la BD, ¿debería estar aquí esta filtración?)
        }

        // 4. AÑADIR FILTROS validados de las localizaciones
         if (!empty($localizaciones_ids)) {
            $placeholders = implode(',', array_fill(0, count($localizaciones_ids), '?'));
            $sql .= " AND EXISTS (SELECT 1 FROM ingredientes_localizaciones 
                                WHERE ingredientes_localizaciones.id_ingrediente = ingredientes.id_ingrediente 
                                AND ingredientes_localizaciones.id_ingrediente IN ($placeholders))";
            $tipos .= str_repeat('i', count($localizaciones_ids));
            $valores = array_merge($valores, $localizaciones_ids);
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
        $ingredientes = [];
        
        // 8. CREAR objetos Ingrediente
        while ($registro = $resultado->fetch_assoc()) {
            $ingrediente = new Ingrediente(
                $registro["id_receta"],
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


}

?>