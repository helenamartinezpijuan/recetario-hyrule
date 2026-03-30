<?php
// /recetario-hyrule/index.php

/**
 * Punto de entrada principal de la aplicación
 * Redirige todas las peticiones a la estructura MVC dentro de /recetario-hyrule/
 */

// Definir constante con la ruta base de la aplicación
define('BASE_PATH', __DIR__ . '/');
//define('BASE_URL', '/recetario-hyrule');
define('BASE_URL', '');

// Incluir el punto de entrada real de la aplicación
require_once BASE_PATH . 'public/home.php';
?>