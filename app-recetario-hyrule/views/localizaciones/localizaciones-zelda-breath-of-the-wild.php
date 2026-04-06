<?php 
$activePage = 'localizaciones';
$titulo = 'Localizaciones - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <!-- Breadcrumb -->
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
        <!-- Barra lateral de filtros -->
        <aside class="filters-sidebar" aria-label="Filtros de búsqueda">
            <h2 class="filters-title">Filtros</h2>
            
            <!-- Filtro por regiones -->
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
        
        <!-- Contenido principal -->
        <div class="localizaciones-content">
            <h1 class="page-title">Todas las localizaciones</h1>
            
            <div id="loader" class="loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando localizaciones...</p>
            </div>
            
            <div id="localizaciones-container">
                <?php if (!empty($localizaciones)): ?>
                    <div class="localizaciones-grid" role="list">
                        <?php foreach ($localizaciones as $localizacion): ?>
                            <article class="localizacion-card" data-id="<?= $localizacion->getIdLocalizacion() ?>">
                                <!---------------------------------------------------------------------------------------------------------->    
                                <div class="localizacion-card-image">
                                    <img src="<?= BASE_URL ?>/resources/img/locations/<?= strtolower(str_replace(' ', '-', $localizacion->getRegion())) ?>/<?= htmlspecialchars($localizacion->getImagen()) ?>" 
                                         alt="<?= htmlspecialchars($localizacion->getNombre()) ?>"
                                         loading="lazy"
                                         onerror="this.src='<?= BASE_URL ?>/resources/img/locations/hyrule.jpg'">
                                </div>
                                <!---------------------------------------------------------------------------------------------------------->
                                <div class="localizacion-card-content">
                                    <span class="region-tag"><?= htmlspecialchars($localizacion->getRegion()) ?></span>
                                    <h2 class="localizacion-title"><?= htmlspecialchars($localizacion->getNombre()) ?></h2>
                                    <p class="localizacion-description"><?= htmlspecialchars($localizacion->getDescripcion()) ?></p>
                                    <button class="btn btn-link view-localizacion" 
                                            data-id="<?= $localizacion->getIdLocalizacion() ?>"
                                            aria-label="Ver detalles de <?= htmlspecialchars($localizacion->getNombre()) ?>">
                                        Ver Localización
                                    </button>
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

<!-- Modal de detalle de localización -->
<div id="localizacion-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-localizacion-title" style="display: none;">
    <div class="modal-overlay" aria-hidden="true"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modal-localizacion-title">Localizacion</h2>
            <button class="modal-close" aria-label="Cerrar modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando detalles...</p>
            </div>
            <div id="modal-localizacion-content"></div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    const BASE_URL = '<?= BASE_URL ?>';
    const $modal = $('#localizacion-modal');

    // Aplicar filtros
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
    
    // Limpiar filtros
    $('#clear-filters').on('click', function() {
        $('.region-filter').prop('checked', false);
        $('#apply-filters').trigger('click');
    });

    // Búsqueda por nombre
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = $(this).val();
            
            $('#loader').show();
            $('#localizaciones-container').fadeOut(200);
            
            $.ajax({
                url: 'index.php?action=buscar_localizaciones',
                method: 'POST',
                data: { nombre: searchTerm },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderLocalizaciones(response.localizaciones);
                        // Actualizar breadcrumb
                        if (searchTerm) {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=localizaciones">Localizaciones</a></li>
                                <li class="breadcrumb-item active">Buscando: ${escapeHtml(searchTerm)}</li>
                            `);
                        } else {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=localizaciones">Localizaciones</a></li>
                                <li class="breadcrumb-item active">Todas las localizaciones</li>
                            `);
                        }
                    } else {
                        showError(response.message || 'Error en la búsqueda');
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
        }, 300);
    });
    
    // Mostrar localizaciones filtradas
    function renderLocalizaciones(localizaciones) {
        const container = $('#localizaciones-container');

        if (!localizaciones || localizaciones.length === 0) {
            container.html('<div class="no-results"><p>No se encontraron localizaciones con los filtros seleccionados.</p></div>');
            return;
        }
        
        let html = '<div class="localizaciones-grid">';
        localizaciones.forEach(localizacion => {
            const regionSlug = localizacion.region.toLowerCase().replace(/ /g, '-');
            html += `
                <article class="localizacion-card" data-id="${localizacion.id_localizacion}">
                    <div class="localizacion-card-image">
                        <img src="${BASE_URL}/resources/img/locations/${regionSlug}/${escapeHtml(localizacion.imagen)}" 
                            alt="${escapeHtml(localizacion.nombre)}"
                            onerror="this.src='${BASE_URL}/resources/img/localizacion/hyrule.jpg'">
                    </div>
                    <div class="localizacion-card-content">
                        <h2 class="localizacion-title">${escapeHtml(localizacion.nombre)}</h2>
                        <div class="localizacion-icons" aria-hidden="true">
                        </div>
                        <p class="localizacion-description">${escapeHtml(localizacion.descripcion)}</p>
                        <button class="btn btn-link view-localizacion" data-id="${localizacion.id_localizacion}">Ver Localización</button>
                    </div>
                </article>
            `;
        });

        html += '</div>';
        container.html(html);
    }

    // Abrir modal de localizacion
    $(document).on('click', '.view-localizacion', function() {
        const localizacionId = $(this).data('id');
        openLocalizacionModal(localizacionId);
    });

    // Obtener información Json
    function openLocalizacionModal(localizacionId) {
        $modal.fadeIn(200);
        $('#modal-localizacion-content').hide();
        $('.modal-loader').show();
        
        $.ajax({
            url: 'index.php?action=obtener_localizacion',
            method: 'GET',
            data: { id: localizacionId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderLocalizacionDetail(response.localizacion);
                } else {
                    $('#modal-localizacion-content').html(`<div class="error-message"><p>${escapeHtml(response.message || 'Error al cargar la localizacion')}</p></div>`);
                }
            },
            error: function() {
                $('#modal-localizacion-content').html('<div class="error-message"><p>Error de conexión</p></div>');
            },
            complete: function() {
                $('.modal-loader').hide();
                $('#modal-localizacion-content').fadeIn(200);
            }
        });
    }
    
    // Mostrar localizacions modal
    function renderLocalizacionDetail(localizacion) {
        const regionSlug = localizacion.region.toLowerCase().replace(/ /g, '-');
        const html = `
            <div class="localizacion-detail">
                <div class="detail-header">
                    <div class="detail-image">
                        <img src="${BASE_URL}/resources/img/locations/${regionSlug}/${escapeHtml(localizacion.imagen)}" 
                             alt="${escapeHtml(localizacion.nombre)}">
                    </div>
                    <div class="detail-info">
                        <h1 class="detail-title">${escapeHtml(localizacion.nombre)}</h1>
                        <p class="detail-description">${escapeHtml(localizacion.descripcion)}</p>
                    </div>
                </div>
            </div>
        `;
        
        $('#modal-localizacion-title').text(localizacion.nombre);
        $('#modal-localizacion-content').html(html);
    }

    // Cerrar modal
    $('.modal-close, .modal-overlay').on('click', function() {
        $modal.fadeOut(200);
        $('#modal-localizacion-content').empty();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $modal.is(':visible')) {
            $modal.fadeOut(200);
        }
    });

    
    function showError(message) {
        $('#localizaciones-container').html(`<div class="error-message"><p>${escapeHtml(message)}</p></div>`);
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>