<?php 
namespace models;

/** 
 * La clase Efecto almacena todos los atributos de la tabla 'efectos' de la base de datos
 */
class Efecto {
  protected $id_efecto;
  protected $id_tipo_efecto;
  protected $imagen;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Efecto
   * @param int $id_efecto Identificador único (clave primaria) del efecto
   * @param TipoEfecto $id_tipo_efecto Nombre del efecto
   * @param string $imagen Ruta a la imagen
   * @param string $descripcion Breve descripción del efecto
   */
  public function __construct($id_efecto, TipoEfecto $id_tipo_efecto, $imagen, $descripcion) {
    $this->setIdEfecto($id_efecto);
    $this->setTipoEfecto($id_tipo_efecto);
    $this->setImagen($imagen);
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
    return $this->id_tipo_efecto;
  }
  /**
   * Función que devuelve la ruta a la imagen del efecto
   * @return string
   */
  public function getImagen(): string {
    return $this->imagen;
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
   * @param TipoEfecto $id_tipo_efecto
   */
  protected function setTipoEfecto($id_tipo_efecto): void {
    $this->id_tipo_efecto = $id_tipo_efecto;
  }
  /**
   * Función que asigna la ruta de la imagen al efecto
   * @param string $imagen
   * @return void
   */
  protected function setImagen($imagen): void {
    $this->imagen = $imagen;
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