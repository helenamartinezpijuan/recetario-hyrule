<?php 
namespace models;

/**
 * La clase Ingrediente almacena todos los atributos de la tabla 'ingredientes' de la base de datos
 */
class Ingrediente {
  protected $id_ingrediente;
  protected $nombre;
  protected $imagen;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Ingrediente
   * @param int $id_ingrediente Identificador único (clave primaria) del ingrediente
   * @param string $nombre Nombre del ingrediente
   * @param string $imagen Ruta a la imagen del ingrediente
   * @param string $descripcion Breve descripción del ingrediente
   */
  public function __construct($id_ingrediente, $nombre, $imagen, $descripcion) {
    $this->setIdIngrediente($id_ingrediente);
    $this->setNombre($nombre);
    $this->setImagen($imagen);
    $this->setDescripcion($descripcion);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID del ingrediente
   * @return int
   */
  public function getIdIngrediente(): int {
    return $this->id_ingrediente;
  }
  /**
   * Función que devuelve el nombre del ingrediente
   * @return string
   */
  public function getNombre(): string {
    return $this->nombre;
  }
  /**
   * Función que devuelve la ruta a la imagen del ingrediente
   * @return string
   */
  public function getImagen(): string {
    return $this->imagen;
  }
   /**
   * Función que devuelve la descripción del ingrediente
   * @return string
   */
  public function getDescripcion(): string {
    return $this->descripcion;
  }


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID al ingrediente
   * @param int $id_ingrediente
   */
  protected function setIdIngrediente($id_ingrediente): void {
    $this->id_ingrediente = $id_ingrediente;
  }
  /**
   * Función que asigna el nombre al ingrediente
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
      $this->nombre = $nombre;
  }
  /**
   * Función que asigna la ruta a la imagen del ingrediente
   * @param string $imagen
   */
  protected function setImagen($imagen): void {
      $this->imagen = $imagen;
  }
  /**
   * Función que asigna la descripción del ingrediente
   * @param string $descripcion
   */
  protected function setDescripcion($descripcion): void {
    $this->descripcion = $descripcion;
  }
}

?>