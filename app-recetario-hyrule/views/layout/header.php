<!DOCTYPE html>
<html lang="es">
<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Recetario de Zelda: Breath of the Wild - Descubre todas las recetas, ingredientes y efectos del juego">
    <meta name="author" content="Helena Martinez Pijuan">

    <title><?php echo $titulo ?? 'Recetario de Hyrule - Zelda: Breath of the Wild'; ?></title>

    <!-- Enlazar con la hoja de estilo css -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/estilos.css">

    <!-- Importar la última versión (3.7.1) de JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Widget de accesibilidad -->
    <div id="accessibility-widget" class="accessibility-widget" aria-label="Panel de accesibilidad">
        <button id="accessibility-toggle" class="accessibility-toggle" aria-expanded="false" aria-controls="accessibility-panel">
            <span aria-hidden="true">♿</span>
            <span class="visually-hidden">Abrir panel de accesibilidad</span>
        </button>
        <div id="accessibility-panel" class="accessibility-panel" hidden>
            <h3>Opciones de accesibilidad</h3>
            <button class="accessibility-option" data-font="openDyslexic">
                <span aria-hidden="true">📖</span> Fuente para disléxicos
            </button>
            <button class="accessibility-option" data-contrast="high">
                <span aria-hidden="true">🌙</span> Alto contraste
            </button>
            <button class="accessibility-option" data-colorblind="protanopia">
                <span aria-hidden="true">🎨</span> Modo protanopía
            </button>
            <button class="accessibility-option" data-reset>
                <span aria-hidden="true">⟳</span> Restablecer
            </button>
        </div>
    </div>

    <!-- Header visual con navegación -->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <a href="?action=home" aria-label="Ir a inicio">
                    <img src="<?= BASE_URL ?>/resources/img/logo-draft-1.png" alt="Recetario Hyrule - Logo">
                </a>
            </div>
            
            <nav class="main-nav" aria-label="Navegación principal">
                <ul class="nav-list">
                    <li><a href="?action=home" class="<?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">HOME</a></li>
                    <li><a href="?action=recetas" class="<?= ($activePage ?? '') === 'recetas' ? 'active' : '' ?>">RECETAS</a></li>
                    <li><a href="?action=ingredientes" class="<?= ($activePage ?? '') === 'ingredientes' ? 'active' : '' ?>">INGREDIENTES</a></li>
                    <li><a href="?action=efectos" class="<?= ($activePage ?? '') === 'efectos' ? 'active' : '' ?>">EFECTOS</a></li>
                    <li><a href="?action=localizaciones" class="<?= ($activePage ?? '') === 'localizaciones' ? 'active' : '' ?>">LOCALIZACIONES</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="main-content">

<script>
// Widget de accesibilidad
$(document).ready(function() {
    const $toggle = $('#accessibility-toggle');
    const $panel = $('#accessibility-panel');
    const $body = $('body');
    
    // Toggle panel
    $toggle.on('click', function() {
        const expanded = $panel.is(':visible');
        $panel.toggle();
        $toggle.attr('aria-expanded', !expanded);
    });
    
    // Cerrar panel al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$toggle.is(e.target) && !$panel.is(e.target) && $panel.has(e.target).length === 0) {
            $panel.hide();
            $toggle.attr('aria-expanded', 'false');
        }
    });
    
    // Cambiar fuente para disléxicos
    $('[data-font="openDyslexic"]').on('click', function() {
        $body.removeClass('font-lexend font-patrick font-amatic');
        $body.addClass('font-opendyslexic');
        localStorage.setItem('accessibility-font', 'openDyslexic');
    });
    
    // Alto contraste
    $('[data-contrast="high"]').on('click', function() {
        $body.toggleClass('high-contrast');
        localStorage.setItem('high-contrast', $body.hasClass('high-contrast'));
    });
    
    // Restablecer
    $('[data-reset]').on('click', function() {
        $body.removeClass('font-opendyslexic font-lexend font-patrick font-amatic high-contrast');
        localStorage.removeItem('accessibility-font');
        localStorage.removeItem('high-contrast');
    });
    
    // Cargar preferencias guardadas
    if (localStorage.getItem('accessibility-font') === 'openDyslexic') {
        $body.addClass('font-opendyslexic');
    }
    if (localStorage.getItem('high-contrast') === 'true') {
        $body.addClass('high-contrast');
    }
});
</script>