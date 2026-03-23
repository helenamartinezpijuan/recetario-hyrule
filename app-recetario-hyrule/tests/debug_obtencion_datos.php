<?php
// tests/debug_obtencion_datos.php
// Prueba aislada del servicio de tipos de vía

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/../services/ClienteService.php';

use services\ClienteService;

echo "<h1>Debug de Tipos de Vía</h1>";

try {
    $service = new ClienteService();
    
    echo "<h2>Método 1: obtenerTiposVia()</h2>";
    $tipos = $service->obtenerTiposVia();
    
    echo "<pre>";
    echo "¿Es array? " . (is_array($tipos) ? '✅ SÍ' : '❌ NO') . "\n";
    echo "¿Está vacío? " . (empty($tipos) ? '❌ SÍ' : '✅ NO') . "\n";
    echo "Número de elementos: " . count($tipos) . "\n";
    echo "Contenido:\n";
    print_r($tipos);
    echo "</pre>";
    
    echo "<h2>Método 2: obtenerTiposVia()</h2>";
    $tipos2 = $service->obtenerTiposVia();
    
    echo "<pre>";
    print_r($tipos2);
    echo "</pre>";
    
    // Probar archivo directamente
    echo "<h2>Archivo de datos directamente</h2>";
    $archivo = __DIR__ . '/../data/clientes/tipos_vias.json';
    echo "Ruta del archivo: $archivo\n";
    echo "¿Existe? " . (file_exists($archivo) ? '✅ SÍ' : '❌ NO') . "\n";
    
    if (file_exists($archivo)) {
        echo "Contenido:\n";
        echo "<pre>" . htmlspecialchars(file_get_contents($archivo)) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}
?>