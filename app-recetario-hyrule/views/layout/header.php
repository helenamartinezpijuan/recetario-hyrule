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
    <div role="region" aria-label="Panel de accesibilidad" class="accessibility-widget" id="accessibility-widget">
        <button id="accessibility-toggle" class="accessibility-toggle" aria-label="Abrir panel de accesibilidad">
            <img src="<?= BASE_URL ?>/resources/img/accesibilidad.png" alt="Accesibilidad" class="accessibility-icon">
        </button>
        <div id="accessibility-panel" class="accessibility-panel" hidden>
            <h3>Opciones de accesibilidad</h3>
            <button class="accessibility-option" data-action="font-dyslexic">
                <span aria-hidden="true">📖</span> Fuente para disléxicos
            </button>
            <button class="accessibility-option" data-action="high-contrast">
                <span aria-hidden="true">🌙</span> Alto contraste
            </button>
            <button class="accessibility-option" data-action="colorblind-protan">
                <span aria-hidden="true">🎨</span> Modo Protanopia/Deuteranopia
            </button>
            <button class="accessibility-option" data-action="colorblind-tritan">
                <span aria-hidden="true">🎨</span> Modo Tritanopia
            </button>
            <button class="accessibility-option" data-action="reset">
                <span aria-hidden="true">⟳</span> Restablecer
            </button>
        </div>
    </div>

    <!-- Header visual con navegación -->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <a href="?action=home" aria-label="Ir a inicio">
                    <img src="<?= BASE_URL ?>/resources/img/logo-draft-2.png" alt="Recetario Hyrule - Logo">
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
// Widget de accesibilidad
$(document).ready(function() {
    const $toggle = $('#accessibility-toggle');
    const $panel = $('#accessibility-panel');
    const $body = $('body');
    
    // Toggle panel - funciona click en cualquier parte del botón
    $toggle.on('click', function(e) {
        e.stopPropagation();
        const expanded = $panel.is(':visible');
        $panel.toggle();
        $toggle.attr('aria-expanded', !expanded);
    });
    
    // Cerrar panel al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$toggle.is(e.target) && !$panel.is(e.target) && !$toggle.find('*').is(e.target) && $panel.has(e.target).length === 0) {
            $panel.hide();
            $toggle.attr('aria-expanded', 'false');
        }
    });
    
    // Cambiar a fuente OpenDyslexic
    $('[data-action="font-dyslexic"]').on('click', function() {
        $body.addClass('font-opendyslexic');
        localStorage.setItem('accessibility-font', 'opendyslexic');
        $panel.hide();
        $toggle.attr('aria-expanded', 'false');
        showAccessibilityMessage('Fuente cambiada a OpenDyslexic');
    });
    
    // Alto contraste
    $('[data-action="high-contrast"]').on('click', function() {
        $body.toggleClass('high-contrast');
        localStorage.setItem('high-contrast', $body.hasClass('high-contrast'));
        $panel.hide();
        $toggle.attr('aria-expanded', 'false');
        showAccessibilityMessage($body.hasClass('high-contrast') ? 'Alto contraste activado' : 'Alto contraste desactivado');
    });
    
    // Modo Protanopia/Deuteranopia (rojo-verde)
    $('[data-action="colorblind-protan"]').on('click', function() {
        $body.removeClass('tritanopia-mode');
        $body.toggleClass('protanopia-mode');
        localStorage.setItem('colorblind-mode', $body.hasClass('protanopia-mode') ? 'protanopia' : '');
        $panel.hide();
        $toggle.attr('aria-expanded', 'false');
        showAccessibilityMessage($body.hasClass('protanopia-mode') ? 'Modo Protanopia activado' : 'Modo Protanopia desactivado');
    });
    
    // Modo Tritanopia (azul-amarillo)
    $('[data-action="colorblind-tritan"]').on('click', function() {
        $body.removeClass('protanopia-mode');
        $body.toggleClass('tritanopia-mode');
        localStorage.setItem('colorblind-mode', $body.hasClass('tritanopia-mode') ? 'tritanopia' : '');
        $panel.hide();
        $toggle.attr('aria-expanded', 'false');
        showAccessibilityMessage($body.hasClass('tritanopia-mode') ? 'Modo Tritanopia activado' : 'Modo Tritanopia desactivado');
    });
    
    // Restablecer
    $('[data-action="reset"]').on('click', function() {
        $body.removeClass('font-opendyslexic high-contrast protanopia-mode tritanopia-mode');
        localStorage.removeItem('accessibility-font');
        localStorage.removeItem('high-contrast');
        localStorage.removeItem('colorblind-mode');
        $panel.hide();
        $toggle.attr('aria-expanded', 'false');
        showAccessibilityMessage('Todas las opciones restablecidas');
    });
    
    // Cargar preferencias guardadas
    if (localStorage.getItem('accessibility-font') === 'opendyslexic') {
        $body.addClass('font-opendyslexic');
    }
    if (localStorage.getItem('high-contrast') === 'true') {
        $body.addClass('high-contrast');
    }
    const colorblindMode = localStorage.getItem('colorblind-mode');
    if (colorblindMode === 'protanopia') {
        $body.addClass('protanopia-mode');
    } else if (colorblindMode === 'tritanopia') {
        $body.addClass('tritanopia-mode');
    }
    
    // Mensaje temporal para feedback
    function showAccessibilityMessage(message) {
        let $msg = $('#accessibility-message');
        if ($msg.length === 0) {
            $msg = $('<div id="accessibility-message" class="accessibility-message"></div>');
            $('body').append($msg);
        }
        $msg.text(message).fadeIn(200).delay(2000).fadeOut(200);
    }

    // Gestionar historial de navegación
    let navigationHistory = [];

    function pushHistory(state, title, url) {
        navigationHistory.push({ state, title, url });
        localStorage.setItem('hyrule_history', JSON.stringify(navigationHistory));
        updateBackButton();
    }

    function goBack() {
        if (navigationHistory.length > 1) {
            navigationHistory.pop();
            const last = navigationHistory[navigationHistory.length - 1];
            localStorage.setItem('hyrule_history', JSON.stringify(navigationHistory));
            window.location.href = last.url;
        } else {
            window.location.href = '?action=home';
        }
    }

    function updateBackButton() {
        $('#back-button').toggle(navigationHistory.length > 1);
    }

    // Cargar historial al iniciar
    const savedHistory = localStorage.getItem('hyrule_history');
    if (savedHistory) {
        navigationHistory = JSON.parse(savedHistory);
        updateBackButton();
    }

    // Guardar cada navegación
    $(document).on('click', 'a:not(.no-history)', function(e) {
        const url = $(this).attr('href');
        if (url && !url.startsWith('#') && !url.startsWith('javascript:')) {
            pushHistory(null, document.title, url);
        }
    });

    $('#back-button').on('click', goBack);

    // Actualizar breadcrumb dinámicamente
    function updateBreadcrumb(path) {
        let html = '<ol class="breadcrumb-list">';
        path.forEach((item, index) => {
            if (index === path.length - 1) {
                html += `<li class="breadcrumb-item active">${escapeHtml(item.name)}</li>`;
            } else {
                html += `<li class="breadcrumb-item"><a href="${item.url}">${escapeHtml(item.name)}</a></li>`;
            }
        });
        html += '</ol>';
        $('.breadcrumb').html(html);
    }
});
</script>