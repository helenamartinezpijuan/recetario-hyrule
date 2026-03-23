<?php
namespace helpers;

class Logger {
    public static function error($mensaje, $archivo = null): void {
        $logFile = __DIR__ . '/../logs/error.log';
        $timestamp = date('Y-m-d H:i:s');
        $info_archivo = $archivo ? " en $archivo" : "";
        $mensaje_completo = "[$timestamp] ERROR: $mensaje$info_archivo\n";
        
        // Asegurar que el directorio logs existe
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }
        
        file_put_contents($logFile, $mensaje_completo, FILE_APPEND);
    }
}