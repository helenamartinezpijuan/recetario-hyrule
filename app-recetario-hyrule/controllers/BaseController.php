<?php
namespace controllers;

use Exception;

/**
 * Clase base para todos los controladores.
 * Contiene métodos comunes como la carga de vistas.
 */
abstract class BaseController {

    /**
     * Carga una vista PHP con datos.
     * @param string $nombre Nombre del archivo de la vista (sin extensión .php)
     * @param array $datos Array asociativo con las variables que se pasarán a la vista
     * @throws Exception Si la vista no existe
     */
    protected function mostrar(string $nombre, array $datos = []): void {
        // 1. CONSTRUIR RUTA completa
        $rutaVista = __DIR__ . '/../views/' . $nombre . '.php';

        // 2. VERIFICAR que la vista existe
        if (!file_exists($rutaVista)) {
            throw new Exception("Vista no encontrada: $nombre");
        }

        // 3. CONVERTIR array asociativo en variables individuales
        extract($datos);

        // 4. INCLUIR la vista
        require_once $rutaVista;
    }

    /**
     * Redirige a una URL.
     * @param string $url Ruta (absoluta o relativa) a la que redirigir
     */
    protected function redirigir(string $url): void {
        header("Location: $url");
        exit;
    }
}
?>