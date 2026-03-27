<?php 
namespace models;

/**
 * La clase Localizacion almacena todos los atributos de la tabla 'localizaciones' de la base de datos
 */
class Localizacion {
  protected $id_localizacion;
  protected $nombre;
  protected $region;
  protected $imagen;
  protected $descripcion;

  /***************
   * CONSTRUCTOR *
   ***************/

  /**
   * Constructor de la clase Localizacion
   * @param int $id_localizacion Identificador único (clave primaria) de la localización
   * @param string $nombre Nombre de la localización
   * @param string $region Enumerador con las regiones de Hyrule
   * @param string $imagen Ruta a la imagen de la localización
   * @param string $descripcion Breve descripción de la localización
   */
  public function __construct($id_localizacion, $nombre, $region, $imagen, $descripcion) {
    $this->setIdLocalizacion($id_localizacion);
    $this->setNombre($nombre);
    $this->setRegion($region);
    $this->setImagen($imagen);
    $this->setDescripcion($descripcion);
  }

  /***********
   * GETTERS *
   ***********/

  /**
   * Función que devuelve el ID de la localización
   * @return int
   */
  public function getIdLocalizacion(): int {
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
   * Función que devuelve la región de la localización
   * @return string
   */
  public function getRegion(): string {
    return $this->region;
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


  /***********
   * SETTERS *
   ***********/

  /**
   * Función que asigna el ID a la localización
   * @param int $id_localizacion
   */
  protected function setIdLocalizacion($id_localizacion): void {
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
   * Función que asigna la región de la localización
   * @param string $region
   */
  protected function setRegion($region): void {
    $this->region = $region;
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
}

?>