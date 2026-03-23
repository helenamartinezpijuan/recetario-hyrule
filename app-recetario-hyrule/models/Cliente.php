<?php 
namespace models;

/**
 * La clase Cliente almacena todos los atributos de la tabla 'clientes' de la base de datos
 */
class Cliente {
  protected $id_cliente;
  protected $dni;
  protected $nombre;
  protected $direccion_postal;
  protected $num_cuenta;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Cliente
   * @param int $id_cliente Identificador único (clave primaria) del cliente
   * @param string $dni Número de DNI del cliente (00000000Z)
   * @param string $nombre Nombre y apellidos del cliente
   * @param string $direccion_postal Dirección postal del cliente
   * @param string $num_cuenta Número de cuenta de cliente (ES00 0000 0000 0000 0000 0000)
   */
  public function __construct($id_cliente, $dni, $nombre, $direccion_postal, $num_cuenta) {
    $this->setIdcliente($id_cliente);
    $this->setDni($dni);
    $this->setNombre($nombre);
    $this->setDireccionPostal($direccion_postal);
    $this->setNumCuenta($num_cuenta);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID del cliente
   * @return int
   */
  public function getIdcliente(): int {
    return $this->id_cliente;
  }
  /**
   * Función que devuelve el DNI del cliente
   * @return string
   */
  public function getDni(): string {
    return $this->dni;
  }
  /**
   * Función que devuelve el nombre completo del cliente
   * @return string
   */
  public function getNombre(): string {
    return $this->nombre;
  }
  /**
   * Función que devuelve la dirección postal del cliente
   * @return string
   */
  public function getDireccionPostal(): string {
    return $this->direccion_postal;
  }
   /**
   * Función que devuelve el número de cuenta (IBAN) del cliente
   * @return string
   */
  public function getNumCuenta(): string {
    return $this->num_cuenta;
  }


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID al cliente
   * @param int $id_cliente
   */
  protected function setIdcliente($id_cliente): void {
    $this->id_cliente = $id_cliente;
  }
  /**
   * Función que asigna el DNI al cliente
   * @param string $dni
   */
  protected function setDni($dni): void {
      $this->dni = $dni;
  }
  /**
   * Función que asigna el nombre completo al cliente
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
      $this->nombre = $nombre;
  }
  /**
   * Función que asigna la dirección postal al cliente
   * @param string $direccion_postal
   */
  protected function setDireccionPostal($direccion_postal): void {
      $this->direccion_postal = $direccion_postal;
  }
  /**
   * Función que asigna el número de cuenta (IBAN) al cliente
   * @param string $num_cuenta
   */
  protected function setNumCuenta($num_cuenta): void {
    $this->num_cuenta = $num_cuenta;
  }
}

?>