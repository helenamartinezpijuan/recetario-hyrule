<?php
// tests/test_db.php
// Prueba la conexión a la base de datos

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../repositories/BaseRepository.php';
require_once __DIR__ . '/../repositories/ClienteRepository.php';

use config\Database;
use repositories\ClienteRepository;

echo "<h1>Test de Base de Datos</h1>";

// Test 1: Conexión simple
echo "<h2>1. Probando conexión</h2>";
try {
    $conn = Database::getConnection();
    echo "<p style='color: green;'>✅ Conexión exitosa</p>";
    
    $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
    $row = $result->fetch_assoc();
    echo "<p>Total clientes en BD: " . $row['total'] . "</p>";
    
    $conn->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 2: Repositorio
echo "<h2>2. Probando ClienteRepository</h2>";
try {
    $repo = new ClienteRepository();
    $clientes = $repo->obtenerTodos();
    
    echo "<p>Clientes obtenidos: " . count($clientes) . "</p>";
    
    if (!empty($clientes)) {
        echo "<p>Primer cliente: " . $clientes[0]->getNombre() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>