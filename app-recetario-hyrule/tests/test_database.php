<?php
/**
 * Test de conexión a base de datos
 * Verifica que la conexión funciona y las tablas existen
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE CONEXIÓN A BASE DE DATOS");

use config\Database;

try {
    // 1. Probar conexión
    $conn = Database::getConnection();
    test_assert(true, "Conexión a base de datos establecida");
    
    // 2. Verificar tablas
    $tables = [
        'recetas',
        'ingredientes',
        'efectos',
        'tipos_efectos',
        'localizaciones',
        'recetas_ingredientes',
        'recetas_efectos',
        'ingredientes_localizaciones'
    ];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        $exists = $result && $result->num_rows > 0;
        test_assert($exists, "Tabla '$table' existe");
        if ($result) $result->free();
    }
    
    // 3. Contar registros
    $counts = [];
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            $counts[$table] = $row['count'];
            $result->free();
        } else {
            $counts[$table] = 'ERROR';
        }
    }
    
    echo PHP_EOL . "📊 Conteo de registros:" . PHP_EOL;
    foreach ($counts as $table => $count) {
        echo "   - $table: $count registros" . PHP_EOL;
    }
    
    // 4. Probar consulta básica en recetas
    $result = $conn->query("SELECT id_receta, nombre FROM recetas LIMIT 5");
    if ($result && $result->num_rows > 0) {
        echo PHP_EOL . "📋 Ejemplo de recetas:" . PHP_EOL;
        while ($row = $result->fetch_assoc()) {
            echo "   - ID {$row['id_receta']}: {$row['nombre']}" . PHP_EOL;
        }
        $result->free();
        test_assert(true, "Consulta básica en recetas funciona");
    } else {
        test_assert(false, "No se pudieron obtener recetas de la base de datos");
    }
    
    $conn->close();
    
} catch (Exception $e) {
    test_assert(false, "Error de conexión: " . $e->getMessage());
}