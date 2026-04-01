<?php
namespace models;

/**
 * La clase RecetaDetalle almacena todos los atributos de varias tablas para agrupar la información de la receta completa
 */
class RecetaDetalle {
    private $receta;
    private $ingredientes = [];
    private $efectos = [];
    
    /**
     * Constructor de la clase RecetaDetalle
     * @param Receta $receta Objeto Receta
     * @param array $ingredientes Array asociativo ['objeto_ingrediente' => Ingrediente, 'cantidad' => int]
     * @param Efecto[] $efectos Array de objetos Efecto
     */
    public function __construct(Receta $receta, array $ingredientes, array $efectos) {
        $this->receta = $receta;
        $this->ingredientes = $ingredientes;
        $this->efectos = $efectos;
    }
    
    /**
     * Función que devuelve el objeto Receta
     * @return Receta
     */
    public function getReceta(): Receta { return $this->receta; }
    /**
     * Función que devuelve un array asociativo de ingredientes y su cantidades
     * @return array Array asociativo con el objeto Ingrediente y su cantidad
     */
    public function getIngredientes(): array { return $this->ingredientes; }
    /**
     * Función que devuelve un array con los efectos
     * @return Efecto[]
     */
    public function getEfectos(): array { return $this->efectos; }
}