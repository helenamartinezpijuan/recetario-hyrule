<?php
/**
 * Test de repositorios
 * Verifica que cada repositorio funciona correctamente
 */

require_once __DIR__ . '/bootstrap.php';

test_section("TEST DE REPOSITORIOS");

use repositories\RecetaRepository;
use repositories\IngredienteRepository;
use repositories\EfectoRepository;
use repositories\LocalizacionRepository;

$all_ok = true;

// 1. Test RecetaRepository
test_log("Probando RecetaRepository...");
try {
    $recetaRepo = new RecetaRepository();
    
    $recetas = $recetaRepo->obtenerTodos();
    test_assert(!empty($recetas), "obtenerTodos() devuelve " . count($recetas) . " recetas");
    
    if (!empty($recetas)) {
        $primeraReceta = $recetas[0];
        $id = $primeraReceta->getIdReceta();
        $receta = $recetaRepo->obtenerPorId($id);
        test_assert($receta !== null, "obtenerPorId($id) funciona");
        test_assert($receta->getIdReceta() === $id, "ID coincide: {$receta->getIdReceta()} === $id");
        
        // Probar búsqueda por nombre
        $nombreBuscado = substr($primeraReceta->getNombre(), 0, 5);
        $encontradas = $recetaRepo->buscarPorNombre($nombreBuscado);
        test_assert(count($encontradas) > 0, "buscarPorNombre('$nombreBuscado') devuelve " . count($encontradas) . " resultados");
    }
} catch (Exception $e) {
    test_assert(false, "Error en RecetaRepository: " . $e->getMessage());
    $all_ok = false;
}

// 2. Test IngredienteRepository
test_log("Probando IngredienteRepository...");
try {
    $ingredienteRepo = new IngredienteRepository();
    
    $ingredientes = $ingredienteRepo->obtenerTodos();
    test_assert(!empty($ingredientes), "obtenerTodos() devuelve " . count($ingredientes) . " ingredientes");
    
    if (!empty($ingredientes)) {
        $primerIng = $ingredientes[0];
        $id = $primerIng->getIdIngrediente();
        $ingrediente = $ingredienteRepo->obtenerPorId($id);
        test_assert($ingrediente !== null, "obtenerPorId($id) funciona");
        
        // Probar búsqueda por nombre
        $encontrados = $ingredienteRepo->buscarPorNombre('carne');
        test_assert(true, "buscarPorNombre('carne') devuelve " . count($encontrados) . " resultados");
    }
} catch (Exception $e) {
    test_assert(false, "Error en IngredienteRepository: " . $e->getMessage());
    $all_ok = false;
}

// 3. Test EfectoRepository
test_log("Probando EfectoRepository...");
try {
    $efectoRepo = new EfectoRepository();
    
    $efectos = $efectoRepo->obtenerTodos();
    test_assert(!empty($efectos), "obtenerTodos() devuelve " . count($efectos) . " efectos");
    
    $tipos = $efectoRepo->obtenerTipos();
    test_assert(!empty($tipos), "obtenerTipos() devuelve " . count($tipos) . " tipos de efecto");
    
} catch (Exception $e) {
    test_assert(false, "Error en EfectoRepository: " . $e->getMessage());
    $all_ok = false;
}

// 4. Test LocalizacionRepository
test_log("Probando LocalizacionRepository...");
try {
    $localizacionRepo = new LocalizacionRepository();
    
    $localizaciones = $localizacionRepo->obtenerTodos();
    test_assert(!empty($localizaciones), "obtenerTodos() devuelve " . count($localizaciones) . " localizaciones");
    
    $regiones = $localizacionRepo->obtenerRegiones();
    test_assert(!empty($regiones), "obtenerRegiones() devuelve " . count($regiones) . " regiones");
    
    if (!empty($regiones)) {
        $primeraRegion = $regiones[0];
        $porRegion = $localizacionRepo->obtenerPorRegion($primeraRegion);
        test_assert(true, "obtenerPorRegion('$primeraRegion') devuelve " . count($porRegion) . " localizaciones");
    }
    
} catch (Exception $e) {
    test_assert(false, "Error en LocalizacionRepository: " . $e->getMessage());
    $all_ok = false;
}

echo PHP_EOL;
test_assert($all_ok, "RESUMEN: Todos los repositorios funcionan", $all_ok ? 'success' : 'error');