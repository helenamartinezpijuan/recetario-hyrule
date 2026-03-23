<?php 
namespace controllers;

use models\Cliente;
use services\ClienteService;
use repositories\ClienteRepository;
use helpers\Logger;
use Exception;


/**
 * La clase ClienteController se encarga de:
 * Recibir datos del formulario
 * Llamar al services
 * Llamar a repositories
 * Cargar la vista
 */
class ClienteController {

    private $repository;
    private $service;

    public function __construct() {
        $this->service = new ClienteService();
        $this->repository = new ClienteRepository();
    }

    public function mostrarFormularioPrincipal(): void {
        try {
            // 1. OBTENER DATOS de los tipos de vías para la dirección postal
            $tipos_via = $this->service->obtenerTiposVia();
            
            // 2. MOSTRAR VISTA del formulario principal
            $this->mostrar('clientes/formulario', [
                'tipos_via' => $tipos_via,
                'base_url' => BASE_URL
            ]);
            
        // 3. MANEJO DE ERRORES
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('clientes/error', [
                'mensaje' => 'Error al cargar el formulario',
                'base_url' => BASE_URL
            ]);
        }
    }

    public function buscarSinFiltros(): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $orden = $_POST['orden_busqueda'] ?? '';

            // 2. BUSCAR en la base de datos
            $clientes = $this->repository->obtenerTodos($orden);

            // 3. MOSTRAR VISTA con los resultados
            $this->mostrar('clientes/resultado', [
                'clientes' => $clientes,
                'base_url' => BASE_URL
            ]);

        // 3. MANEJO DE ERRORES
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('clientes/error', [
                'mensaje' => 'Error en la búsqueda',
                'base_url' => BASE_URL
            ]);
        }
    }
    
    /**
     * Busca clientes aplicando los filtros del formulario
     * @param array $postData Los datos completos de $_POST
     * @return void
     */
    public function buscarConFiltros(array $postData): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $dni = $postData['buscar_dni'] ?? '';
            $nombre = $postData['buscar_nombre'] ?? '';
            $direccion = $postData['buscar_direccion_postal'] ?? '';
            $numCuenta = $postData['buscar_num_cuenta'] ?? '';
            
            // 2. VALIDAR Y NORMALIZAR los datos del formulario
            $filtros = $this->service->prepararFiltrosBusqueda($dni, $nombre, $direccion, $numCuenta);
            
            // 3. BUSCAR en la base de datos
            $clientes = $this->repository->buscarPorFiltros($filtros);
            
            // 4. MOSTRAR VISTA con los resultados
            $this->mostrar('clientes/resultado', [
                'clientes' => $clientes,
                'base_url' => BASE_URL
            ]);

        // 5. MANEJO DE ERRORES    
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('clientes/error', [
                'mensaje' => 'Error en la búsqueda',
                'base_url' => BASE_URL
            ]);
        }
    }

    /**
     * Crear un nuevo registro para la tabla 'clientes'
     * @param mixed $postData Los datos completos de $_POST
     * @throws Exception Si no se puede validar el nuevo cliente antes de insertarlo
     * @return void
     */
    public function crear($postData): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $dni_num = $postData['crear_dni'] ?? '';
            $dni_letra = $postData['letra_dni'] ?? '';
            $nombre = $postData['crear_nombre'] ?? ''; 
            $tipo_via = $postData['crear_tipo_via'] ?? '';
            $direccion = $postData['crear_direccion_postal'] ?? '';
            $municipio = $postData['crear_municipio'] ?? '';
            $num_cuenta = $postData['crear_num_cuenta'] ?? '';
            // Reconstruir los datos del formulario
            $dni = $dni_num . $dni_letra;
            $direccion_postal = $this->service->construirDireccion($tipo_via, $direccion, $municipio);

            // 2. VALIDAR Y NORMALIZAR los datos del formulario
            if ( !$this->service->validarCliente($dni, $nombre, $direccion_postal, $num_cuenta) ) {
                throw new Exception('No se puede crear el cliente');
            } else {
                $nuevo_cliente = new Cliente(0, $dni, $nombre, $direccion_postal, 'ES' . $num_cuenta); // ESTA SOLUCION ES TERRIBLE. MODIFICAR CODIGO PARA CORREGIR EN: service, models y formulario
            }
            
            // 3. INSERTAR nuevo registro
            $cliente = $this->repository->insertar($nuevo_cliente);
            
            // 4. MOSTRAR VISTA de éxito
            $this->mostrar('clientes/creado', [
                'cliente' => $cliente,
                'base_url' => BASE_URL
            ]);
            
        // 5. MANEJO DE ERRORES 
        } catch (Exception $e) {
            Logger::error($e->getMessage(), __FILE__);
            
            $this->mostrar('clientes/error', [
                'mensaje' => 'No se pudo crear el cliente. Por favor, inténtelo más tarde.',
                'detalle' => $e->getMessage(),
                'base_url' => BASE_URL
            ]);
        }
    }

    /**
     * Actualizar registro de la tabla 'clientes'
     * @param mixed $postData Los datos completos de $_POST
     * @throws Exception Si no se puede actualizar el cliente
     * @return void
     */
    public function actualizar($postData): void {
        try {
            // 1. EXTRAER DATOS del formulario
            $id_cliente = $postData['id_cliente'] ?? '';
            $dni = $postData['actualizar_dni'] ?? '';
            $nombre = $postData['actualizar_nombre'] ?? '';
            $direccion = $postData['actualizar_direccion_postal'] ?? '';
            $numCuenta = $postData['actualizar_num_cuenta'] ?? '';

            // 2. VALIDAR DATOS con el service
            if ( !$this->service->validarCliente($dni, $nombre, $direccion, $numCuenta) ) {
                throw new Exception('No se puede actualizar el cliente'); // REVISAR MANEJO DE ERRORES - DE ESTO SE DEBERÍA ENCARGAR EL SERVICE ?!!
            } else {
                $cliente_actualizado = new Cliente(0, $dni, $nombre, $direccion, $numCuenta);
            }

            // 3. ACTUALIZAR en la base de datos con el repository
            $clienteActualizado = $this->repository->actualizar($cliente_actualizado);

            if ($clienteActualizado) {
                $mensaje = 'Cliente actualizado correctamente';
            } else {
                $mensaje = 'No se detectaron cambios en los datos';
            }

            // 4. MOSTRAR VISTA
            $this->mostrar('clientes/actualizado', [
                'cliente' => $clienteActualizado,
                'mensaje' => $mensaje,
                'base_url' => BASE_URL
            ]);

        // 5. MANEJO DE ERRORES 
        } catch (Exception $e) {   
            Logger::error($e->getMessage(), __FILE__);
            $this->mostrar('clientes/error', [
                'mensaje' => 'Error al actualizar el cliente',
                'detalle' => $e->getMessage(),
                'base_url' => BASE_URL
            ]);
        }
    }
    
    /**
     * Método auxiliar para cargar vistas
     * @param string $nombre Nombre del archivo de la vista
     * @param array $datos Array asociativo con los datos que la vista necesita
     * @throws Exception Si la vista no existe
     */
    private function mostrar(string $nombre, array $datos = []): void {
        // Construir ruta completa
        $rutaVista = __DIR__ . '/../views/' . $nombre . '.php';

        // Verificar que la vista existe
        if (!file_exists($rutaVista)) {
            throw new Exception("Vista no encontrada: $nombre");
        }

        // Convertir array asociativo en variables individuales
        extract($datos);

        // Incluir la vista
        require_once $rutaVista;
    }
}


?>