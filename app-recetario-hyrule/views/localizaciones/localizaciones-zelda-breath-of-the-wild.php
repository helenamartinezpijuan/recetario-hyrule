<?php 
$activePage = 'localizaciones';
$titulo = 'Localizaciones - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <nav class="breadcrumb" aria-label="Ruta de navegación">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="?action=localizaciones">Localizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page">Todas las localizaciones</li>
        </ol>
    </nav>
    <!-- Botón volver atrás -->
    <button id="back-button" class="btn-back" style="display: none;" aria-label="Volver atrás">
        ← Volver
    </button>

    <!-- Barra buscador -->
    <div class="search-bar-container">
        <input type="text" id="search-input" class="search-input-large" 
            placeholder="🔍 Buscar localizaciones..." aria-label="Buscar localizaciones por nombre">
    </div>
    
    <div class="page-layout">
        <aside class="filters-sidebar" aria-label="Filtros de búsqueda">
            <h2 class="filters-title">Filtros</h2>
            
            <div class="filter-group">
                <h3 class="filter-category" id="filter-regiones-heading">Regiones</h3>
                <ul class="filter-list" aria-labelledby="filter-regiones-heading">
                    <?php if (!empty($regiones)): ?>
                        <?php foreach ($regiones as $region): ?>
                            <li>
                                <label class="checkbox-label">
                                    <input type="checkbox" class="filter-checkbox region-filter" 
                                        value="<?= htmlspecialchars($region) ?>"
                                        aria-label="Filtrar por región <?= htmlspecialchars($region) ?>">
                                    <?= htmlspecialchars($region) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="filter-actions">
                <button id="apply-filters" class="btn btn-primary">Aplicar filtros</button>
                <button id="clear-filters" class="btn btn-secondary">Limpiar filtros</button>
            </div>
        </aside>
        
        <div class="localizaciones-content">
            <h1 class="page-title">Todas las localizaciones</h1>
            
            <div id="loader" class="loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando localizaciones...</p>
            </div>
            
            <div id="localizaciones-container">
                <?php if (!empty($localizaciones)): ?>
                    <div class="localizaciones-grid">
                        <?php foreach ($localizaciones as $localizacion): ?>
                            <article class="localizacion-card">
                                <div class="localizacion-card-image">
                                    <img src="<?= BASE_URL ?>/resources/img/locations/<?= strtolower(str_replace(' ', '-', $localizacion->getRegion())) ?>/<?= htmlspecialchars($localizacion->getImagen()) ?>" 
                                         alt="<?= htmlspecialchars($localizacion->getNombre()) ?>"
                                         loading="lazy">
                                </div>
                                <div class="localizacion-card-content">
                                    <span class="region-tag"><?= htmlspecialchars($localizacion->getRegion()) ?></span>
                                    <h2 class="localizacion-title"><?= htmlspecialchars($localizacion->getNombre()) ?></h2>
                                    <p class="localizacion-description"><?= htmlspecialchars($localizacion->getDescripcion()) ?></p>
                                    <a href="?action=obtener_localizacion&id=<?= $localizacion->getIdLocalizacion() ?>" 
                                       class="btn btn-link">
                                        Ver ingredientes de esta zona →
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results" role="status">
                        <p>No se encontraron localizaciones con los filtros seleccionados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#apply-filters').on('click', function() {
        const regiones = [];
        $('.region-filter:checked').each(function() {
            regiones.push($(this).val());
        });
        
        $('#loader').show();
        $('#localizaciones-container').fadeOut(200);
        
        $.ajax({
            url: 'index.php?action=filtrar_localizaciones',
            method: 'POST',
            data: { regiones: regiones },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderLocalizaciones(response.localizaciones);
                } else {
                    showError(response.message || 'Error al cargar las localizaciones');
                }
            },
            error: function() {
                showError('Error de conexión con el servidor');
            },
            complete: function() {
                $('#loader').hide();
                $('#localizaciones-container').fadeIn(200);
            }
        });
    });
    
    $('#clear-filters').on('click', function() {
        $('.region-filter').prop('checked', false);
        $('#apply-filters').trigger('click');
    });
    
    function renderLocalizaciones(localizaciones) {
        const container = $('#localizaciones-container');
        if (!localizaciones || localizaciones.length === 0) {
            container.html('<div class="no-results"><p>No se encontraron localizaciones con los filtros seleccionados.</p></div>');
            return;
        }
        
        let html = '<div class="localizaciones-grid">';
        localizaciones.forEach(loc => {
            html += `
                <article class="localizacion-card">
                    <div class="localizacion-card-image">
                        <img src="${BASE_URL}/resources/img/locations/${escapeHtml(loc.region.toLowerCase().replace(/ /g, '-'))}/${escapeHtml(loc.imagen)}" 
                             alt="${escapeHtml(loc.nombre)}">
                    </div>
                    <div class="localizacion-card-content">
                        <span class="region-tag">${escapeHtml(loc.region)}</span>
                        <h2 class="localizacion-title">${escapeHtml(loc.nombre)}</h2>
                        <p class="localizacion-description">${escapeHtml(loc.descripcion)}</p>
                        <a href="?action=obtener_localizacion&id=${loc.id_localizacion}" class="btn btn-link">
                            Ver ingredientes de esta zona →
                        </a>
                    </div>
                </article>
            `;
        });
        html += '</div>';
        container.html(html);
    }
    
    const BASE_URL = '<?= BASE_URL ?>';
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#39;');
    }
    
    function showError(message) {
        $('#localizaciones-container').html(`<div class="error-message"><p>${escapeHtml(message)}</p></div>`);
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>