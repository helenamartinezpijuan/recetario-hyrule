<?php 
$activePage = 'ingredientes';
$titulo = 'Ingredientes - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <nav class="breadcrumb" aria-label="Ruta de navegación">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="?action=ingredientes">Ingredientes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Todos los ingredientes</li>
        </ol>
    </nav>
    <!-- Botón volver atrás -->
    <button id="back-button" class="btn-back" style="display: none;" aria-label="Volver atrás">
        ← Volver
    </button>

    <!-- Barra buscador -->
    <div class="search-bar-container">
        <input type="text" id="search-input" class="search-input-large" 
            placeholder="🔍 Buscar ingredientes..." aria-label="Buscar ingredientes por nombre">
    </div>
    
    <div class="page-layout">
        <!-- Barra lateral de filtros -->
        <aside class="filters-sidebar" aria-label="Filtros de búsqueda">
            <h2 class="filters-title">Filtros</h2>
            
            <!-- Búsqueda por nombre -->
            <!--<div class="filter-group">
                <h3 class="filter-category">Buscar</h3>
                <input type="text" id="search-ingrediente" class="search-input" 
                       placeholder="Nombre del ingrediente..." aria-label="Buscar ingrediente por nombre">
            </div>-->
            
            <!-- Filtro por categorías -->
            <div class="filter-group">
                <h3 class="filter-category" id="filter-categorias-heading">Categorías</h3>
                <ul class="filter-list" aria-labelledby="filter-categorias-heading">
                    <li>
                        <label class="checkbox-label">
                            <input type="checkbox" class="filter-checkbox categoria-filter" value="setas">
                            Setas
                        </label>
                    </li>
                    <li>
                        <label class="checkbox-label">
                            <input type="checkbox" class="filter-checkbox categoria-filter" value="pescados_mariscos">
                            Pescados y Mariscos
                        </label>
                    </li>
                    <li>
                        <label class="checkbox-label">
                            <input type="checkbox" class="filter-checkbox categoria-filter" value="vegetales_flores_frutas">
                            Vegetales, Flores y Frutas
                        </label>
                    </li>
                    <li>
                        <label class="checkbox-label">
                            <input type="checkbox" class="filter-checkbox categoria-filter" value="insectos_reptiles_monstruo">
                            Insectos, Reptiles y Partes de Monstruo
                        </label>
                    </li>
                </ul>
            </div>
            
            <!-- Filtro por localizaciones -->
            <div class="filter-group">
                <h3 class="filter-category" id="filter-localizaciones-heading">Localizaciones</h3>
                <ul class="filter-list" aria-labelledby="filter-localizaciones-heading">
                    <?php if (!empty($localizaciones)): ?>
                        <?php foreach ($localizaciones as $localizacion): ?>
                            <li>
                                <label class="checkbox-label">
                                    <input type="checkbox" class="filter-checkbox localizacion-filter" 
                                           value="<?= $localizacion->getIdLocalizacion() ?>">
                                    <?= htmlspecialchars($localizacion->getNombre()) ?>
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
        <div class="ingredientes-content">
            <h1 class="page-title">Todos los ingredientes</h1>
            
            <div id="loader" class="loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando ingredientes...</p>
            </div>
            
            <div id="ingredientes-container">
                <?php if (!empty($ingredientes)): ?>
                    <div class="ingredientes-grid">
                        <?php foreach ($ingredientes as $ingrediente): ?>
                            <article class="ingrediente-card" data-id="<?= $ingrediente->getIdIngrediente() ?>">
                                <div class="ingrediente-card-image">
                                    <img src="<?= BASE_URL ?>/resources/img/ingredients/<?= htmlspecialchars($ingrediente->getImagen()) ?>" 
                                         alt="<?= htmlspecialchars($ingrediente->getNombre()) ?>"
                                         loading="lazy">
                                </div>
                                <div class="ingrediente-card-content">
                                    <h2 class="ingrediente-title"><?= htmlspecialchars($ingrediente->getNombre()) ?></h2>
                                    <div class="ingrediente-icons" aria-hidden="true">
                                        <span class="ingrediente-icon">🥕</span>
                                        <span class="ingrediente-icon">🍴</span>
                                    </div>
                                    <p class="ingrediente-description"><?= htmlspecialchars($ingrediente->getDescripcion()) ?></p>
                                    <a href="?action=obtener_ingrediente&id=<?= $ingrediente->getIdIngrediente() ?>" 
                                       class="btn btn-link"
                                       aria-label="Ver detalles de <?= htmlspecialchars($ingrediente->getNombre()) ?>">
                                        Ver Ingrediente
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results" role="status">
                        <p>No se encontraron ingredientes con los filtros seleccionados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalle de ingrediente -->
<div id="ingrediente-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-ingrediente-title" style="display: none;">
    <div class="modal-overlay" aria-hidden="true"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modal-ingrediente-title">Ingrediente</h2>
            <button class="modal-close" aria-label="Cerrar modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando detalles...</p>
            </div>
            <div id="modal-ingrediente-content"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Búsqueda por nombre
    let searchTimeout;
    $('#search-ingrediente').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            aplicarFiltros();
        }, 300);
    });
    
    // Aplicar filtros
    $('#apply-filters').on('click', aplicarFiltros);
    
    // Limpiar filtros
    $('#clear-filters').on('click', function() {
        $('.filter-checkbox').prop('checked', false);
        $('#search-ingrediente').val('');
        aplicarFiltros();
    });
    
    function aplicarFiltros() {
        const categorias = [];
        const localizaciones = [];
        const nombre = $('#search-ingrediente').val();
        
        $('.categoria-filter:checked').each(function() {
            categorias.push($(this).val());
        });
        
        $('.localizacion-filter:checked').each(function() {
            localizaciones.push($(this).val());
        });
        
        $('#loader').show();
        $('#ingredientes-container').fadeOut(200);
        
        $.ajax({
            url: 'index.php?action=filtrar_ingredientes',
            method: 'POST',
            data: {
                ingrediente_categoria: categorias,
                localizaciones: localizaciones,
                nombre: nombre
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderIngredientes(response.ingredientes);
                } else {
                    showError(response.message || 'Error al cargar los ingredientes');
                }
            },
            error: function() {
                showError('Error de conexión con el servidor');
            },
            complete: function() {
                $('#loader').hide();
                $('#ingredientes-container').fadeIn(200);
            }
        });
    }
    
    function renderIngredientes(ingredientes) {
        const container = $('#ingredientes-container');
        if (!ingredientes || ingredientes.length === 0) {
            container.html('<div class="no-results"><p>No se encontraron ingredientes con los filtros seleccionados.</p></div>');
            return;
        }
        
        let html = '<div class="ingredientes-grid">';
        ingredientes.forEach(ing => {
            html += `
                <article class="ingrediente-card" data-id="${ing.id_ingrediente}">
                    <div class="ingrediente-card-image">
                        <img src="${BASE_URL}/resources/img/ingredients/${escapeHtml(ing.imagen)}" 
                             alt="${escapeHtml(ing.nombre)}"
                             onerror="this.src='${BASE_URL}/resources/img/ingredients/placeholder.jpg'">
                    </div>
                    <div class="ingrediente-card-content">
                        <h2 class="ingrediente-title">${escapeHtml(ing.nombre)}</h2>
                        <div class="ingrediente-icons" aria-hidden="true">
                            <span class="ingrediente-icon">🥕</span>
                            <span class="ingrediente-icon">🍴</span>
                        </div>
                        <p class="ingrediente-description">${escapeHtml(ing.descripcion)}</p>
                        <a href="?action=obtener_ingrediente&id=${ing.id_ingrediente}" class="btn btn-link">
                            Ver Ingrediente
                        </a>
                    </div>
                </article>
            `;
        });
        html += '</div>';
        container.html(html);
    }
    
    function showError(message) {
        $('#ingredientes-container').html(`<div class="error-message"><p>${escapeHtml(message)}</p></div>`);
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

    // Abrir modal de ingrediente
    $(document).on('click', '.view-ingrediente', function() {
        const id = $(this).data('id');
        openIngredienteModal(id);
    });

    function openIngredienteModal(id) {
        const $modal = $('#ingrediente-modal');
        $modal.fadeIn(200);
        $('#modal-ingrediente-content').hide();
        $('.modal-loader').show();

        $.ajax({
            url: 'index.php?action=obtener_ingrediente',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderIngredienteDetail(response.ingrediente);
                } else {
                    $('#modal-ingrediente-content').html('<div class="error-message"><p>Error al cargar el ingrediente</p></div>');
                }
            },
            error: function() {
                $('#modal-ingrediente-content').html('<div class="error-message"><p>Error de conexión</p></div>');
            },
            complete: function() {
                $('.modal-loader').hide();
                $('#modal-ingrediente-content').fadeIn(200);
            }
        });
    }

    function renderIngredienteDetail(ingrediente) {
        const html = `
            <div class="detail-header">
                <div class="detail-image">
                    <img src="${BASE_URL}/resources/img/ingredients/${ingrediente.imagen}" alt="${ingrediente.nombre}">
                </div>
                <div class="detail-info">
                    <h1 class="detail-title">${escapeHtml(ingrediente.nombre)}</h1>
                    <p class="detail-description">${escapeHtml(ingrediente.descripcion)}</p>
                </div>
            </div>
            <div class="detail-section">
                <h3>📍 Localizaciones</h3>
                <div id="ingrediente-localizaciones">
                    <div class="loader-mini" style="display: block;">Cargando localizaciones...</div>
                </div>
            </div>
        `;
        $('#modal-ingrediente-title').text(ingrediente.nombre);
        $('#modal-ingrediente-content').html(html);
        
        // Cargar localizaciones del ingrediente
        $.ajax({
            url: 'index.php?action=obtener_localizaciones_por_ingrediente',
            method: 'GET',
            data: { id: ingrediente.id_ingrediente },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.localizaciones.length > 0) {
                    let locHtml = '<ul class="localizaciones-list">';
                    response.localizaciones.forEach(loc => {
                        locHtml += `<li>
                            <strong>${escapeHtml(loc.nombre)}</strong> 
                            <span class="region-badge">${escapeHtml(loc.region)}</span>
                        </li>`;
                    });
                    locHtml += '</ul>';
                    $('#ingrediente-localizaciones').html(locHtml);
                } else {
                    $('#ingrediente-localizaciones').html('<p>Este ingrediente se encuentra en todo Hyrule.</p>');
                }
            },
            error: function() {
                $('#ingrediente-localizaciones').html('<p>No se pudieron cargar las localizaciones.</p>');
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>