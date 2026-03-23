<?php
// tests/test_vista.php
require_once __DIR__ . '/bootstrap.php';

// ============================================
// DATOS SIMULADOS (como si vinieran del controlador)
// ============================================

// Simular tipos de vía (como si vinieran de ClienteService)
$tipos_via_simulados = [
    'Calle',
    'Avenida',
    'Plaza',
    'Paseo',
    'Ronda',
    'Travesía',
    'Camino',
    'Carretera',
    'Glorieta',
    'Urbanización'
];

// Simular otros datos que podría necesitar la vista
$base_url_simulada = '/clinica_veterinaria';
$titulo_pagina = '🧪 TEST - Formulario de Clientes (Modo Aislado)';

// ============================================
// INCLUIR EL HEADER
// ============================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo $titulo_pagina; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Estilos - probar diferentes rutas -->
    <link rel="stylesheet" href="/clinica_veterinaria/public/estilos.css">
    <style>
        /* Estilos de emergencia por si el CSS no carga */
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test-header { background: #ff6b6b; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .test-badge { background: #4ecdc4; color: white; padding: 3px 10px; border-radius: 3px; font-size: 0.9em; }
        fieldset { background: white; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; padding: 20px; }
        legend { background: white; padding: 5px 15px; border: 1px solid #ddd; border-radius: 20px; font-weight: bold; }
        label { display: block; margin: 10px 0; }
        input, select { padding: 8px; border: 1px solid #ccc; border-radius: 3px; }
        .debug-info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-family: monospace; }
        .success { color: #4caf50; }
        .warning { color: #ff9800; }
        .error { color: #f44336; }
    </style>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<!-- BANNER DE TEST -->
<div class="test-header">
    <h1>🧪 MODO TEST - VISTA AISLADA</h1>
    <p>Esta vista está usando datos SIMULADOS, no de la base de datos real</p>
    <span class="test-badge">Test: formulario.php</span>
</div>

<!-- INFORMACIÓN DE DEBUG -->
<div class="debug-info">
    <h3>📊 Datos simulados en esta prueba:</h3>
    <ul>
        <li><strong>Tipos de vía:</strong> <?php echo count($tipos_via_simulados); ?> elementos</li>
        <li><strong>Base URL:</strong> <?php echo $base_url_simulada; ?></li>
        <li><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
    </ul>
    
    <h4>Contenido de \$tipos_via_simulados:</h4>
    <pre><?php print_r($tipos_via_simulados); ?></pre>
</div>

<!-- ============================================
     AQUÍ COMIENZA EL FORMULARIO REAL (copia de views/cliente/formulario.php)
     ============================================ -->

<div id="contenedor-clientes">
    <h2>Formulario Clientes</h2>

    <!-- Formulario para buscar clientes -->
    <form action="<?php echo $base_url_simulada; ?>/public/index.php" method="post">
        <input type="hidden" name="action" value="buscarConFiltros">

        <fieldset>
            <legend id="click-buscar-cliente">🔍 Buscar clientes (TEST)</legend>
            <div id="buscar-cliente">
                <fieldset>
                    <legend>Filtros de búsqueda</legend>
                    
                    <label>DNI:
                        <input type="text" name="buscar_dni" maxlength="9" placeholder="12345678Z" value="12345678Z">
                    </label>
                    
                    <label>Nombre:
                        <input type="text" name="buscar_nombre" placeholder="Nombre Apellidos" value="Carlos">
                    </label>
                    
                    <label>Dirección:
                        <input type="text" name="buscar_direccion_postal" placeholder="Calle..." value="Calle Mayor">
                    </label>
                    
                    <label>Nº Cuenta:
                        <input type="text" name="buscar_num_cuenta" placeholder="ES00..." value="ES10">
                    </label>
                    
                    <input type="submit" name="buscar_cliente" value="Buscar con filtros">
                    <input type="submit" name="buscar_cliente_sin_filtro" value="Buscar todos" 
                           onclick="this.form.action.value='buscarSinFiltros'">
                </fieldset>
            </div>
        </fieldset>
    </form>

    <!-- Formulario para crear cliente -->
    <form action="<?php echo $base_url_simulada; ?>/public/index.php" method="post">
        <input type="hidden" name="action" value="crear">

        <fieldset>
            <legend id="click-crear-cliente">➕ Crear cliente (TEST)</legend>
            <div id="crear-cliente">
                <fieldset>
                    <legend>Datos personales</legend>
                    
                    <label>DNI:
                        <input type="text" name="crear_dni" id="crear-numero-dni" 
                               maxlength="8" placeholder="12345678" 
                               onkeydown="soloNumeros(event)" 
                               oninput="asignarLetraDni()" 
                               value="12345678" required>
                        <input type="text" name="letra_dni" id="crear-letra-dni" 
                               size="1" placeholder="Z" readonly>
                        <small>(La letra se calcula automáticamente)</small>
                    </label>
                    
                    <label>Nombre y Apellidos:
                        <input type="text" name="crear_nombre" maxlength="50" 
                               placeholder="Nombre Apellido1 Apellido2" 
                               value="Juan Pérez García" required>
                    </label>
                </fieldset>

                <fieldset>
                    <legend>Dirección postal</legend>
                    
                    <label>Tipo de vía:
                        <select name="crear_tipo_via">
                            <?php foreach ($tipos_via_simulados as $via): ?>
                                <option value="<?php echo htmlspecialchars($via); ?>" 
                                    <?php echo ($via === 'Calle') ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($via); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small>✅ DATOS SIMULADOS: <?php echo count($tipos_via_simulados); ?> opciones</small>
                    </label>
                    
                    <label>Dirección:
                        <input type="text" name="crear_direccion_postal" maxlength="120" 
                               placeholder="Nombre de la calle 123" 
                               value="Falsa 123" required>
                    </label>
                    
                    <label>Municipio:
                        <input type="text" name="crear_municipio" 
                               value="Madrid" required>
                    </label>
                </fieldset>

                <fieldset>
                    <legend>Datos de pago</legend>
                    
                    <label>Número de cuenta (IBAN):
                        <input type="text" name="prefijo_iban" size="2" value="ES" readonly>
                        <input type="text" name="crear_num_cuenta" minlength="22" maxlength="22" 
                               placeholder="00 0000 0000 0000 0000 0000" 
                               value="1000000000000000000001" required>
                    </label>
                </fieldset>

                <input type="submit" name="crear_cliente" value="Crear cliente (TEST)">
            </div>
        </fieldset>
    </form>

    <!-- Formulario para actualizar clientes -->
    <form action="<?php echo $base_url_simulada; ?>/public/index.php" method="post">
        <fieldset>
            <legend id="click-actualizar-cliente">✏️ Actualizar clientes (TEST)</legend>
            <div id="actualizar-cliente">
                <fieldset>
                    <legend>Actualizar un registro</legend>
                    
                    <label>ID del cliente a actualizar:
                        <input type="number" name="id_cliente" value="1" min="1">
                    </label>
                    
                    <fieldset>
                        <legend>Ordenar registros por:</legend>
                        <label><input type="radio" name="busqueda_actualizar" value="dni" checked> DNI</label>
                        <label><input type="radio" name="busqueda_actualizar" value="nombre"> Nombre</label>
                        <label><input type="radio" name="busqueda_actualizar" value="direccion"> Dirección</label>
                        <label><input type="radio" name="busqueda_actualizar" value="cuenta"> Nº Cuenta</label>
                    </fieldset>
                    
                    <input type="submit" name="actualizar_cliente_busqueda" value="Buscar registros">
                </fieldset>
            </div>
        </fieldset>
    </form>

    <!-- Formulario para eliminar clientes -->
    <form action="<?php echo $base_url_simulada; ?>/public/index.php" method="post">
        <fieldset>
            <legend id="click-eliminar-cliente">🗑️ Eliminar clientes (TEST)</legend>
            <div id="eliminar-cliente">
                <fieldset>
                    <legend>Eliminar por ID</legend>
                    <label>ID del cliente:
                        <input type="number" name="id_cliente" min="1" placeholder="ID a eliminar">
                    </label>
                    <input type="submit" name="eliminar_cliente" value="Eliminar cliente">
                </fieldset>
            </div>
        </fieldset>
    </form>
</div>

<!-- ============================================
     JAVASCRIPT (copiado de tu formulario original)
     ============================================ -->

<script>
$(document).ready(function() {
    // Ocultar todos los formularios inicialmente
    $("#buscar-cliente").hide();
    $("#crear-cliente").hide();
    $("#actualizar-cliente").hide();
    $("#eliminar-cliente").hide();

    // Toggles para los desplegables
    $("#click-buscar-cliente").click(function() {
        $("#buscar-cliente").slideToggle();
    });
    
    $("#click-crear-cliente").click(function() {
        $("#crear-cliente").slideToggle();
    });
    
    $("#click-actualizar-cliente").click(function() {
        $("#actualizar-cliente").slideToggle();
    });
    
    $("#click-eliminar-cliente").click(function() {
        $("#eliminar-cliente").slideToggle();
    });
    
    // Abrir automáticamente el formulario de crear para test
    $("#crear-cliente").show();
});

function soloNumeros(event) {
    var codigo = event.keyCode;
    if (
        (codigo >= 48 && codigo <= 57) ||      // Números arriba
        (codigo >= 96 && codigo <= 105) ||     // Números teclado numérico
        codigo === 8 ||                        // Backspace
        codigo === 9 ||                        // Tab
        codigo === 13 ||                       // Enter
        codigo === 46 ||                       // Delete
        (codigo >= 37 && codigo <= 40)         // Flechas
    ) {
        return;
    } else {
        event.preventDefault();
    }
}

function calcularLetraDni(num_dni) {
    const letras = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E'];
    return letras[num_dni % 23];
}

function asignarLetraDni() {
    let num_dni = document.getElementById("crear-numero-dni").value;
    if (num_dni.length === 8 && /^\d+$/.test(num_dni)) {
        document.getElementById("crear-letra-dni").value = calcularLetraDni(parseInt(num_dni));
    } else {
        document.getElementById("crear-letra-dni").value = "";
    }
}

// Ejecutar al cargar la página para el valor por defecto
window.onload = function() {
    asignarLetraDni();
};
</script>

<!-- ============================================
     FOOTER con información de depuración
     ============================================ -->

<div style="margin-top: 50px; padding: 20px; background: #f0f0f0; border-radius: 5px;">
    <h3>📋 Información de depuración:</h3>
    <ul>
        <li><strong>Archivo:</strong> tests/test_vista.php</li>
        <li><strong>Fecha test:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
        <li><strong>Tipos de vía:</strong> 
            <ul>
                <?php foreach ($tipos_via_simulados as $via): ?>
                    <li><?php echo htmlspecialchars($via); ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    
    <p>
        <strong>✅ Este test usa datos SIMULADOS</strong> - No conecta con la base de datos real.<br>
        <strong>🎯 Objetivo:</strong> Verificar que el HTML, CSS y JavaScript funcionan correctamente.
    </p>
</div>

</body>
</html>