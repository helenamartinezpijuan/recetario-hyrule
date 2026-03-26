<?php 
namespace repositories;

use models\Localizacion;
use Exception;

/**
 * La clase LocalizacionRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'localizaciones' de la base de datos
 */
class LocalizacionRepository extends BaseRepository {

    /**
     * Buscar todas localizaciones (sin filtros)
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

    /**
     * Buscar localizaciones por nombre
     * @param string $nombre Nombre de la receta introducido por el usuario
     * @throws Exception Si hay error en la consulta
     * @return Localizacion[] Array de objetos Localizacion
     */
    public function buscarPorNombre(string $nombre): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_localizacion, nombre, region, imagen, descripcion 
            FROM localizaciones 
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
        $localizaciones = [];
        
        // 7. CREAR objetos Localizacion
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
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $localizaciones;
    }

    /**
     * Obtener todas las regiones disponibles
     * @return array Array de strings con los nombres de las regiones
     */
    public function obtenerRegiones(): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT DISTINCT region FROM localizaciones;";

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
     * Obtener todas las localizaciones de una región específica
     * @param string $region Región de la que obtener sus localizaciones
     * @return Localizacion[] Array de objetos Localizacion
     */
    public function obtenerPorRegion(string $region): array {
        // 1. VALIDAR parámetro de entrada

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_localizacion, nombre, imagen, descripcion FROM localizaciones WHERE region=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda de localizaciones por región"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("s", $region);

        // 6. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda de localizaciones por región"); }

        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $localizaciones = [];

        // 8. CREAR objetos Localizacion
        while ($registro = $resultado->fetch_assoc()) {
            $localizacion = new Localizacion(
                $registro["id_localizacion"],
                $registro["nombre"],
                $region,
                $registro["imagen"],
                $registro["descripcion"]
            );
            $localizaciones[] = $localizacion;
        }

        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $localizaciones;
    }

    /**
     * Obtener todas las localizaciones de varias regiones
     * @param array $regiones Regiones de las que obtener sus localizaciones
     * @return Localizacion[] Array de objetos Localizacion
     */
    public function obtenerPorRegiones(array $regiones): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_localizacion, nombre, imagen, descripcion FROM localizaciones WHERE 1=1";
        $tipos = 's';
        $valores = [];

        // 3. AÑADIR FILTROS validados de las regiones
        if (!empty($regiones)) {
            foreach ($regiones as $region) {
                $sql .= " AND region=?"; // * count($regiones)
            }
        }
        
        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por regiones"); }
        
        // 5. VINCULAR PARÁMETROS si hay filtros
        if (!empty($valores)) {
            // Uso de operador splat (...) para evitar un switch(count($valores))
            $statement->bind_param($tipos, ...$valores);
        }
        
        // 6. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por regiones"); }
        
        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $localizaciones = [];
        
        // 8. CREAR objetos Receta
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
        
        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $localizaciones;
    }

}

?>