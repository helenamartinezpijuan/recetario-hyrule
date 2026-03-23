<?php 
namespace models;

/**
 * La clase Localizacion almacena todos los atributos de la tabla 'localizaciones' de la base de datos
 */
class Localizacion {
  protected $id_localizacion;
  protected $nombre;
  protected $imagen;
  protected $descripcion;
  protected $region;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Localizacion
   * @param int $id_localizacion Identificador único (clave primaria) de la localización
   * @param string $nombre Nombre de la localización
   * @param string $imagen Ruta a la imagen de la localización
   * @param string $descripcion Breve descripción de la localización
   */
  public function __construct($id_localizacion, $nombre, $imagen, $descripcion, $region) {
    $this->setIdIngrediente($id_localizacion);
    $this->setNombre($nombre);
    $this->setImagen($imagen);
    $this->setDescripcion($descripcion);
    $this->setRegion($region);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID de la localización
   * @return int
   */
  public function getIdIngrediente(): int {
    return $this->id_localizacion;
  }
  /**
   * Función que devuelve el nombre de la localización
   * @return string
   */
  public function getNombre(): string {
    return $this->nombre;
  }
  /**
   * Función que devuelve la ruta a la imagen de la localización
   * @return string
   */
  public function getImagen(): string {
    return $this->imagen;
  }
   /**
   * Función que devuelve la descripción de la localización
   * @return string
   */
  public function getDescripcion(): string {
    return $this->descripcion;
  }
  /**
   * Función que devuelve la región de la localización
   * @return string
   */
  public function getRegion(): string {
    return $this->region;
  }


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID a la localización
   * @param int $id_localizacion
   */
  protected function setIdIngrediente($id_localizacion): void {
    $this->id_localizacion = $id_localizacion;
  }
  /**
   * Función que asigna el nombre a la localización
   * @param string $nombre
   */
  protected function setNombre($nombre): void {
      $this->nombre = $nombre;
  }
  /**
   * Función que asigna la ruta a la imagen de la localización
   * @param string $imagen
   */
  protected function setImagen($imagen): void {
      $this->imagen = $imagen;
  }
  /**
   * Función que asigna la descripción de la localización
   * @param string $descripcion
   */
  protected function setDescripcion($descripcion): void {
    $this->descripcion = $descripcion;
  }
  /**
   * Función que asigna la región de la localización
   * @param string $region
   */
  protected function setRegion($region): void {
    $this->region = $region;
  }
}

?>