<?php 
namespace models;

/** 
 * La clase TipoEfecto almacena todos los atributos de la tabla 'tipos_efectos' de la base de datos
 */
class TipoEfecto {
  protected $id_tipo_efecto;
  protected $nombre;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase TipoEfecto
   * @param int $id_tipo_efecto Identificador único (clave primaria) del tipo de efecto
   * @param string $nombre Nombre del efecto
   */
  public function __construct($id_tipo_efecto, $nombre) {
    $this->setIdTipoEfecto($id_tipo_efecto);
    $this->setNombre($nombre);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID del efecto
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


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID al tipo de efecto
   * @param int $id_tipo_efecto
   */
  protected function setIdTipoEfecto($id_tipo_efecto): void {
    $this->id_tipo_efecto = $id_tipo_efecto;
  }
  /**
   * Función que asigna el nombre del efecto
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
    $this->nombre = $nombre;
  }
}

?>