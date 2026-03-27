<?php

namespace repositories;

use config\Database;
use helpers\Logger;
use Exception;

abstract class BaseRepository {
    
    protected function getConnection() {
        $conn = Database::getConnection();
        return $conn;
    }

    /**
     * Maneja errores de base de datos y lanza excepción apropiada
     * @param mixed $conn La conexión o statement con error
     * @param string $contexto Descripción de dónde ocurrió el error
     * @throws Exception Siempre lanza una excepción
     */
    protected function handleError($conn, string $contexto): void {
        $error = $conn->error ?? 'Error desconocido';
        $errno = $conn->errno ?? 0;
        
        // Log del error
        Logger::error("Error en $contexto: $error (código: $errno)", __FILE__);
        
        // Lanzar excepción con mensaje apropiado según el código
        switch ( $errno ) {
            // [...] añadir más casos específicos para imprimir mensajes de error user friendly
            case 1062:
                throw new Exception("Ya existe un registro con estos datos.");
            default:
                throw new Exception("Error en la operación de base de datos.");
        }
    }
}