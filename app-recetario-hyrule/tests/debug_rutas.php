<?php
// tests/debug_rutas.php
// Este script prueba rutas absolutas y relativas

echo "<h1>Debug de Rutas</h1>";

// Ruta absoluta desde la raíz del servidor
echo "<h2>Ruta absoluta (recomendada):</h2>";
echo "<link rel='stylesheet' href='/practicas-php/clinica_veterinaria/public/estilos.css'>";
echo "<p>URL: /practicas-php/clinica_veterinaria/public/estilos.css</p>";
echo "<p>¿Existe el archivo? " . (file_exists($_SERVER['DOCUMENT_ROOT'] . '/practicas-php/clinica_veterinaria/public/estilos.css') ? '✅ SÍ' : '❌ NO') . "</p>";

// Ruta relativa desde tests/
echo "<h2>Ruta relativa desde tests/:</h2>";
echo "<link rel='stylesheet' href='../public/estilos.css'>";
echo "<p>URL: ../public/estilos.css</p>";
echo "<p>¿Existe el archivo? " . (file_exists(__DIR__ . '/../public/estilos.css') ? '✅ SÍ' : '❌ NO') . "</p>";

// Tu ruta actual (la incorrecta)
echo "<h2>Tu ruta actual:</h2>";
echo "<link rel='stylesheet' href='/../../public/estilos.css'>";
echo "<p>URL: /../../public/estilos.css</p>";
echo "<p>Esto es INCORRECTO porque /../../ intenta subir desde la raíz del servidor</p>";

// Mostrar información del servidor
echo "<h2>Información del servidor:</h2>";
echo "<pre>";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "__DIR__ actual: " . __DIR__ . "\n";
echo "Ruta completa al CSS: " . $_SERVER['DOCUMENT_ROOT'] . '/practicas-php/clinica_veterinaria/public/estilos.css' . "\n";
echo "</pre>";
?>