<?php
// tests/debug_controller.php
// Prueba el controlador sin pasar por index.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../services/ClienteService.php';
require_once __DIR__ . '/../repositories/BaseRepository.php';
require_once __DIR__ . '/../repositories/ClienteRepository.php';
require_once __DIR__ . '/../controllers/ClienteController.php';

use controllers\ClienteController;

echo "<h1>Debug de ClienteController</h1>";

try {
    $controller = new ClienteController();
    
    echo "<h2>Simulando mostrarFormularioPrincipal()</h2>";
    
    // Usamos Reflection para acceder al método privado 'mostrar' y capturar lo que pasa
    $reflection = new ReflectionClass($controller);
    $metodoMostrar = $reflection->getMethod('mostrar');
    $metodoMostrar->setAccessible(true);
    
    // Simulamos que llamamos a mostrarFormularioPrincipal
    echo "<p>✅ Controlador instanciado correctamente</p>";
    
    // Verificar que el servicio tiene tipos de vía
    echo "<h3>Verificando ClienteService:</h3>";
    $service = $reflection->getProperty('service');
    $service->setAccessible(true);
    $serviceInstance = $service->getValue($controller);
    
    $tipos = $serviceInstance->obtenerTiposVia();
    echo "<pre>";
    echo "Tipos de vía desde service: " . count($tipos) . " elementos\n";
    print_r($tipos);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>