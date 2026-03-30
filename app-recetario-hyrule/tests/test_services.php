<?php
/**
 * Test de servicios
 * Verifica que cada servicio funciona correctamente
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE SERVICIOS");

use services\RecetaService;
use services\IngredienteService;
use services\EfectoService;
use services\LocalizacionService;

$all_ok = true;

// 1. Test RecetaService
test_log("Probando RecetaService...");
try {
    $recetaService = new RecetaService();
    
    $recetas = $recetaService->getAllRecetas();
    test_assert(is_array($recetas), "getAllRecetas() devuelve array con " . count($recetas) . " recetas");
    
    $tiposEfectos = $recetaService->getAllTiposEfectos();
    test_assert(is_array($tiposEfectos), "getAllTiposEfectos() devuelve array con " . count($tiposEfectos) . " tipos");
    
    // Usamos sortIngredientesPorCategoria() en su lugar.
    $ingredientesPorCategoria = $recetaService->sortIngredientesPorCategoria();
    test_assert(is_array($ingredientesPorCategoria), "sortIngredientesPorCategoria() devuelve array con " . count($ingredientesPorCategoria) . " categorías");
    
    if (!empty($recetas)) {
        $primeraReceta = $recetas[0];
        $detalle = $recetaService->getRecetaDetalle($primeraReceta->getIdReceta());
        test_assert($detalle !== null, "getRecetaDetalle() funciona para receta ID " . $primeraReceta->getIdReceta());
        
        $filtradas = $recetaService->getRecetasFiltradas([], []);
        test_assert(is_array($filtradas), "getRecetasFiltradas() con filtros vacíos funciona");
    }
    
} catch (Exception $e) {
    test_assert(false, "Error en RecetaService: " . $e->getMessage());
    $all_ok = false;
}

// 2. Test IngredienteService
test_log("Probando IngredienteService...");
try {
    $ingredienteService = new IngredienteService();
    
    $ingredientes = $ingredienteService->getAllIngredientes();
    test_assert(is_array($ingredientes), "getAllIngredientes() devuelve " . count($ingredientes) . " ingredientes");
    
    $categorias = $ingredienteService->sortIngredientesPorCategoria();
    test_assert(is_array($categorias), "sortIngredientesPorCategoria() devuelve " . count($categorias) . " categorías");
    
    $buscados = $ingredienteService->buscarIngredientesPorNombre('carne');
    test_assert(is_array($buscados), "buscarIngredientesPorNombre('carne') devuelve " . count($buscados) . " resultados");
    
    $filtrados = $ingredienteService->getIngredientesFiltrados([], []);
    test_assert(is_array($filtrados), "getIngredientesFiltrados() funciona");
    
} catch (Exception $e) {
    test_assert(false, "Error en IngredienteService: " . $e->getMessage());
    $all_ok = false;
}

// 3. Test EfectoService
test_log("Probando EfectoService...");
try {
    $efectoService = new EfectoService();
    
    $efectos = $efectoService->getAllEfectos();
    test_assert(is_array($efectos), "getAllEfectos() devuelve " . count($efectos) . " efectos");
    
    $tipos = $efectoService->getAllTiposEfectos();
    test_assert(is_array($tipos), "getAllTiposEfectos() devuelve " . count($tipos) . " tipos");
    
    if (!empty($efectos)) {
        $primerEfecto = $efectos[0];
        $porId = $efectoService->getEfectoPorId($primerEfecto->getIdEfecto());
        test_assert($porId !== null, "getEfectoPorId() funciona");
    }
    
} catch (Exception $e) {
    test_assert(false, "Error en EfectoService: " . $e->getMessage());
    $all_ok = false;
}

// 4. Test LocalizacionService
test_log("Probando LocalizacionService...");
try {
    $localizacionService = new LocalizacionService();
    
    $localizaciones = $localizacionService->getAllLocalizaciones();
    test_assert(is_array($localizaciones), "getAllLocalizaciones() devuelve " . count($localizaciones) . " localizaciones");
    
    $regiones = $localizacionService->getRegionesDisponibles();
    test_assert(is_array($regiones), "getRegionesDisponibles() devuelve " . count($regiones) . " regiones");
    
    $porRegion = $localizacionService->getLocalizacionesPorRegion();
    test_assert(is_array($porRegion), "getLocalizacionesPorRegion() funciona");
    
    $filtradas = $localizacionService->getLocalizacionesFiltradas([]);
    test_assert(is_array($filtradas), "getLocalizacionesFiltradas() funciona");
    
} catch (Exception $e) {
    test_assert(false, "Error en LocalizacionService: " . $e->getMessage());
    $all_ok = false;
}

echo PHP_EOL;
test_assert($all_ok, "RESUMEN: Todos los servicios funcionan", $all_ok ? 'success' : 'error');