<?php 

namespace repositories;

use models\Cliente;
use Exception;

/**
 * La clase ClienteRepository se encarga de manejar la lógica de las consultas SQL para la tabla 'clientes' de la base de datos. Por norma general, las funciones siguen el siguiente orden lógico:
 *  1. VALIDAR los parámetros de la función
 *  2. OBTENER CONEXIÓN a la base de datos
 *  3. CONSTRUIR CONSULTA SQL
 *  4. PREPARAR CONSULTA parametrizada
 *  5. VINCULAR PARÁMETROS a la consulta parametrizada
 *  6. EJECUTAR CONSULTA con manejo de errores
 *  7. OBTENER RESULTADOS o VERIFICAR MODIFICACIÓN
 *  8. CREAR nuevo objeto (o array de objetos) Cliente
 *  9. LIMPIEZA de la conexión
 */
class ClienteRepository extends BaseRepository {

    /**
     * El array COLUMNAS_PERMITIDAS especifica las columnas existentes en la tabla 'clientes' de la base de datos
     * @var array Strings con los nombres de las columnas de la tabla 'clientes'
     */
    public const COLUMNAS_PERMITIDAS = [
        'id_cliente', 
        'dni', 
        'nombre', 
        'direccion_postal', 
        'num_cuenta'
    ];


    /**************************************
     * BUSCAR - Consulta SELECT sin WHERE *
     **************************************/
    /**
     * Buscar clientes sin aplicar filtros
     * @param string $orden Columna de la tabla de la base de datos por la que ordenar el resultado de la consulta
     * @throws Exception Si hay error en la consultas
     * @return Cliente[] Array de objetos Cliente
     */
    public function obtenerTodos(string $orden = 'nombre'): array {
        // 1. VALIDAR parámetros de entrada
        if (!in_array($orden, self::COLUMNAS_PERMITIDAS)) {
            $orden = 'nombre'; // Valor por defecto
        }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT id_cliente, dni, nombre, direccion_postal, num_cuenta FROM clientes ORDER BY $orden";

        // 4. EJECUTAR CONSULTA con manejo de errores
        $resultado = $conn->query($sql);
        if (!$resultado) { $this->handleError($conn, "consultando todos los clientes"); }

        // 5. OBTENER RESULTADOS
        $clientes = [];

        // 6. CREAR objeto Cliente
        while ($registro = $resultado->fetch_assoc()) {
            $cliente = new Cliente(
                $registro["id_cliente"],
                $registro["dni"],
                $registro["nombre"],
                $registro["direccion_postal"],
                $registro["num_cuenta"]
            );
            $clientes[] = $cliente;
        }

        // 7. LIMPIEZA
        $resultado->close();
        $conn->close();
    
        return $clientes;
    }

    /*********************************************
     * BUSCAR - Consulta SELECT con WHERE id=$id *
     *********************************************/
    /**
     * Buscar cliente por su ID
     * @param int $id_cliente Identificador único del cliente
     * @throws Exception Si hay error en la consulta
     * @return Cliente Objeto Cliente con ID = $id_cliente
     */
    public function buscarPorId(int $id_cliente): ?Cliente {
        // 1. VALIDAR parámetros de entrada
        if ($id_cliente <= 0) { throw new Exception("No se puede buscar un cliente sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "SELECT dni, nombre, direccion_postal, num_cuenta FROM clientes WHERE id_cliente=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando búsqueda por ID"); }

        // 5. VINCULAR PARÁMETROS a la consulta
        $statement->bind_param("i", $id_cliente);

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
        $cliente = new Cliente(
            $id_cliente,
            $fila['dni'],
            $fila['nombre'],
            $fila['direccion_postal'],
            $fila['num_cuenta']
        );

        // 11. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $cliente;
    }

    /**************************************
     * BUSCAR - Consulta SELECT con WHERE *
     **************************************/
    /**
     * Busca clientes aplicando filtros
     * @param array $filtros Array asociativo con filtros validados
     * @throws Exception Si hay error en la consulta
     * @return array Array de objetos Cliente
     */
    public function buscarPorFiltros(array $filtros): array {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();
        
        // 2. CONSTRUIR CONSULTA
        $sql = "SELECT id_cliente, dni, nombre, direccion_postal, num_cuenta FROM clientes WHERE 1=1";
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
            // Utilizamos el operador splat (...) para evitar un switch(count($valores))
            $statement->bind_param($tipos, ...$valores);
        }
        
        // 6. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando búsqueda por filtros"); }
        
        // 7. OBTENER RESULTADOS
        $resultado = $statement->get_result();
        $clientes = [];
        
        // 8. CREAR objetos Cliente
        while ($registro = $resultado->fetch_assoc()) {
            $cliente = new Cliente(
                $registro["id_cliente"],
                $registro["dni"],
                $registro["nombre"],
                $registro["direccion_postal"],
                $registro["num_cuenta"]
            );
            $clientes[] = $cliente;
        }
        
        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
        
        return $clientes;
    }

    /********************************
     * CREAR - Consulta INSERT INTO *
     ********************************/
    /**
     * Crear nuevo registro de cliente
     * @param Cliente $cliente Objeto Cliente a insertar en la base de 
     * @throws Exception Si hay error en la consulta
     * @return Cliente Objeto Cliente con el ID_cliente
     */
    public function insertar(Cliente $cliente): ?Cliente {
        // 1. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 2. CONSTRUIR CONSULTA
        $sql = "INSERT INTO clientes (dni, nombre, direccion_postal, num_cuenta) VALUES (?, ?, ?, ?)";

        // 3. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando inserción de cliente"); }

        // 4. EXTRAER VALORES del objeto Cliente (necesario para bind_param no lance un warning porque necesita una referencia a la variable original, no solo el valor de retorno de una función)
        $dni = $cliente->getDni();
        $nombre = $cliente->getNombre();
        $direccion = $cliente->getDireccionPostal();
        $cuenta = $cliente->getNumCuenta();

        // 5. VINCULAR PARÁMETROS obtenidos del objeto Cliente a la consulta parametrizada
        $statement->bind_param("ssss", $dni, $nombre, $direccion, $cuenta);

        // 6. EJECUTAR CONSULTA
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando inserción de cliente"); }

        // 7. OBTENER el ID del cliente generado por la base de datos
        $id_generado = $statement->insert_id;
        
        // 8. LIMPIEZA
        $statement->close();
        $conn->close();

        return new Cliente($id_generado, $dni, $nombre, $direccion, $cuenta);
    }

    /********************************
     * ACTUALIZAR - Consulta UPDATE *
     ********************************/
    /**
     * Actualizar un cliente existente
     * @param Cliente $cliente Objeto Cliente con los datos actualizados
     * @throws Exception Si hay error en la operación
     * @return bool True si la actualización se realiza con éxito
     */
    public function actualizar(Cliente $cliente): bool {
        // 1. VALIDAR parámetros de entrada
        $id_cliente = $cliente->getIdcliente();
        if (empty($id_cliente)) { throw new Exception("No se puede actualizar un cliente sin ID"); }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "UPDATE clientes SET dni=?, nombre=?, direccion_postal=?, num_cuenta=? WHERE id_cliente=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando actualización de cliente"); }

        // 5. EXTRAER VALORES del objeto Cliente
        $dni = $cliente->getDni();
        $nombre = $cliente->getNombre();
        $direccion = $cliente->getDireccionPostal();
        $cuenta = $cliente->getNumCuenta();

        // 6. VINCULAR PARÁMETROS
        $statement->bind_param("ssssi", 
            $dni, 
            $nombre, 
            $direccion, 
            $cuenta, 
            $id_cliente
        );

        // 7. EJECUTAR CONSULTA con manejo de errores
        if (!$statement->execute()) { $this->handleError($statement, "ejecutando actualización de cliente"); }

        // 8. VERIFICAR si la actualización ha modificado algún dato
        $actualizado = $statement->affected_rows > 0;

        // 9. LIMPIEZA
        $statement->close();
        $conn->close();
    
        return $actualizado;
    }

    /******************************
     * ELIMINAR - Consulta DELETE *
     ******************************/
    /**
     * Eliminar cliente por su ID
     * @param int $id_cliente Identificador único del cliente
     * @throws Exception Si hay error en la consulta
     * @return bool True si la eliminación se realiza con éxito
     */
    public function eliminarPorId(int $id_cliente): bool {
        // 1. VALIDAR parámetros de entrada
        if ($id_cliente <= 0) { return false; }

        // 2. OBTENER CONEXIÓN
        $conn = $this->getConnection();

        // 3. CONSTRUIR CONSULTA
        $sql = "DELETE FROM clientes WHERE id_cliente=?;";

        // 4. PREPARAR CONSULTA parametrizada
        $statement = $conn->prepare($sql);
        if (!$statement) { $this->handleError($conn, "preparando eliminación"); }

        // 5. EJECUTAR CONSULTA con manejo de errores
        $statement->bind_param("i", $id_cliente);
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