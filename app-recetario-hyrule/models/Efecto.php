<?php 
namespace models;

/**
 * La clase Efecto almacena todos los atributos de las tablas 'efectos' y 'tipos_efectos' de la base de datos
 */
class Efecto {
  protected $id_efecto;
  protected $id_tipo_efecto;
  protected $nombre;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Efecto
   * @param int $id_efecto Identificador único (clave primaria) del efecto
   * @param int $id_tipo_efecto Identificador único (clave primaria) del tipo de efecto
   * @param string $nombre Nombre del efecto
   * @param string $descripcion Breve descripción del efecto
   */
  public function __construct($id_efecto, $id_tipo_efecto, $nombre, $descripcion) {
    $this->setIdEfecto($id_efecto);
    $this->setIdTipoEfecto($id_tipo_efecto);
    $this->setNombre($nombre);
    $this->setDescripcion($descripcion);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID del efecto
   * @return int
   */
  public function getIdEfecto(): int {
    return $this->id_efecto;
  }
  /**
   * Función que devuelve el ID del tipo de efecto
   * @return int
   */
  public function getIdTipoEfecto(): int {
    return $this->id_tipo_efecto;
  }
  /**
   * Función que devuelve el nombre del efecto
   * @return string
   */
  public function getNombre(): string {
    return $this->nombre;
  }
   /**
   * Función que devuelve la descripción del efecto
   * @return string
   */
  public function getDescripcion(): string {
    return $this->descripcion;
  }


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID al efecto
   * @param int $id_efecto
   */
  protected function setIdEfecto($id_efecto): void {
    $this->id_efecto = $id_efecto;
  }
  /**
   * Función que asigna el ID al tipo de efecto
   * @param int $id_tipo_efecto
   */
  protected function setIdTipoEfecto($id_tipo_efecto): void {
    $this->id_tipo_efecto = $id_tipo_efecto;
  }
  /**
   * Función que asigna el nombre al efecto
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
      $this->nombre = $nombre;
  }
  /**
   * Función que asigna la descripción del efecto
   * @param string $descripcion
   */
  protected function setDescripcion($descripcion): void {
    $this->descripcion = $descripcion;
  }
}

?>