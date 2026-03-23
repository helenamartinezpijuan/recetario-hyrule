<?php 
namespace models;

/**
 * La clase Receta almacena todos los atributos de la tabla 'recetas' de la base de datos
 */
class Receta {
  protected $id_receta;
  protected $nombre;
  protected $imagen;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Receta
   * @param int $id_receta Identificador único (clave primaria) de la receta
   * @param string $nombre Nombre de la receta
   * @param string $imagen Ruta a la imagen de la receta
   * @param string $descripcion Breve descripción de la receta
   */
  public function __construct($id_receta, $nombre, $imagen, $descripcion) {
    $this->setIdReceta($id_receta);
    $this->setNombre($nombre);
    $this->setImagen($imagen);
    $this->setDescripcion($descripcion);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID de la receta
   * @return int
   */
  public function getIdReceta(): int {
    return $this->id_receta;
  }
  /**
   * Función que devuelve el nombre de la receta
   * @return string
   */
  public function getNombre(): string {
    return $this->nombre;
  }
  /**
   * Función que devuelve la ruta a la imagen de la receta
   * @return string
   */
  public function getImagen(): string {
    return $this->imagen;
  }
   /**
   * Función que devuelve la descripción de la receta
   * @return string
   */
  public function getDescripcion(): string {
    return $this->descripcion;
  }


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID a la receta
   * @param int $id_receta
   */
  protected function setIdReceta($id_receta): void {
    $this->id_receta = $id_receta;
  }
  /**
   * Función que asigna el nombre a la receta
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
      $this->nombre = $nombre;
  }
  /**
   * Función que asigna la ruta a la imagen de la receta
   * @param string $imagen
   */
  protected function setImagen($imagen): void {
      $this->imagen = $imagen;
  }
  /**
   * Función que asigna la descripción de la receta
   * @param string $descripcion
   */
  protected function setDescripcion($descripcion): void {
    $this->descripcion = $descripcion;
  }
}

?>