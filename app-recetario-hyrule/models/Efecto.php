<?php 
namespace models;

/** 
 * La clase Efecto almacena todos los atributos de la tabla 'efectos' de la base de datos
 */
class Efecto {
  protected $id_efecto;
  protected $tipo_efecto;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Efecto
   * @param int $id_efecto Identificador único (clave primaria) del efecto
   * @param TipoEfecto $tipo_efecto Nombre del efecto
   * @param string $descripcion Breve descripción del efecto
   */
  public function __construct($id_efecto, TipoEfecto $tipo_efecto, $descripcion) {
    $this->setIdEfecto($id_efecto);
    $this->setTipoEfecto($tipo_efecto);
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
   * Función que devuelve el tipo del efecto
   * @return TipoEfecto
   */
  public function getTipoEfecto(): TipoEfecto {
    return $this->tipo_efecto;
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
   * Función que asigna el tipo al efecto
   * @param TipoEfecto $tipo_efecto
   */
  protected function setTipoEfecto($tipo_efecto): void {
      $this->tipo_efecto = $tipo_efecto;
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