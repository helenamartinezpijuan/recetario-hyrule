<?php
// tests/test_header.php
// Prueba el header con diferentes rutas de CSS

// Probar diferentes rutas
$rutas_css = [
    'Ruta 1 (absoluta)' => '/practicas-php/clinica_veterinaria/public/estilos.css',
    'Ruta 2 (relativa desde tests)' => '../public/estilos.css',
    'Ruta 3 (desde raíz documento)' => $_SERVER['DOCUMENT_ROOT'] . '/practicas-php/clinica_veterinaria/public/estilos.css',
];

echo "<h1>Test de Rutas CSS</h1>";

foreach ($rutas_css as $nombre => $ruta) {
    $ruta_fisica = str_replace('/practicas-php/clinica_veterinaria', $_SERVER['DOCUMENT_ROOT'] . '/practicas-php/clinica_veterinaria', $ruta);
    if (strpos($ruta, '..') === 0) {
        $ruta_fisica = __DIR__ . '/' . $ruta;
    }
    
    $existe = file_exists($ruta_fisica);
    
    echo "<h3>$nombre</h3>";
    echo "<p>Ruta: $ruta</p>";
    echo "<p>Ruta física: $ruta_fisica</p>";
    echo "<p>¿Existe? " . ($existe ? '✅ SÍ' : '❌ NO') . "</p>";
    
    if ($existe) {
        echo "<link rel='stylesheet' href='$ruta'>";
        echo "<p style='background: lightgreen; padding: 10px;'>Si ves este recuadro verde con estilo, el CSS funciona</p>";
    }
}
?>