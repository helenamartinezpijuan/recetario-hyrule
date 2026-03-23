<?php
// tests/test_index.php
// Página principal de pruebas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de Pruebas - Clínica Veterinaria</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; margin: 20px; }
        .test-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .test-card { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .test-card h3 { margin-top: 0; }
        .btn { display: inline-block; padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🧪 Panel de Pruebas - Clínica Veterinaria</h1>
    
    <div class="test-grid">
        <div class="test-card">
            <h3>🔍 Debug de Rutas</h3>
            <p>Prueba las rutas absolutas/relativas del CSS</p>
            <a href="debug_rutas.php" class="btn" target="_blank">Abrir</a>
        </div>
        
        <div class="test-card">
            <h3>📋 Debug Tipos de Vía</h3>
            <p>Prueba el servicio de tipos de vía aislado</p>
            <a href="debug_tipos_via.php" class="btn" target="_blank">Abrir</a>
        </div>
        
        <div class="test-card">
            <h3>🎮 Debug Controlador</h3>
            <p>Prueba el controlador sin index.php</p>
            <a href="debug_controller.php" class="btn" target="_blank">Abrir</a>
        </div>
        
        <div class="test-card">
            <h3>💾 Test Base de Datos</h3>
            <p>Prueba la conexión a la BD</p>
            <a href="test_db.php" class="btn" target="_blank">Abrir</a>
        </div>
        
        <div class="test-card">
            <h3>👁️ Test Vista Aislada</h3>
            <p>Prueba el formulario con datos simulados</p>
            <a href="test_vista.php" class="btn" target="_blank">Abrir</a>
        </div>
        
        <div class="test-card">
            <h3>🎨 Test Header/CSS</h3>
            <p>Prueba diferentes rutas de CSS</p>
            <a href="test_header.php" class="btn" target="_blank">Abrir</a>
        </div>
    </div>
</body>
</html>