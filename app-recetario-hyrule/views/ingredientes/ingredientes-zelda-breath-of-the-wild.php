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
            
            <!-- Filtro por categorías de ingredientes -->
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
    const BASE_URL = '<?= BASE_URL ?>';
    const $modal = $('#ingrediente-modal');

    // Aplicar filtros
    $('#apply-filters').on('click', function() {
        const categorias = [];
        const localizaciones = [];
        
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
    });
    
    // Búsqueda por nombre
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = $(this).val();
            
            $('#loader').show();
            $('#ingredientes-container').fadeOut(200);
            
            $.ajax({
                url: 'index.php?action=buscar_ingredientes',
                method: 'POST',
                data: { nombre: searchTerm },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderIngredientes(response.ingredientes);
                        // Actualizar breadcrumb
                        if (searchTerm) {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=ingredientes">Ingredientes</a></li>
                                <li class="breadcrumb-item active">Buscando: ${escapeHtml(searchTerm)}</li>
                            `);
                        } else {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=ingredientes">Ingredientes</a></li>
                                <li class="breadcrumb-item active">Todas los ingredientes</li>
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
                    $('#recipes-container').fadeIn(200);
                }
            });
        }, 300);
    });
    
    // Limpiar filtros
    $('#clear-filters').on('click', function() {
        $('.filter-checkbox').prop('checked', false);
        $('#apply-filters').trigger('click');
    });
    
    // Mostrar ingredientes filtrados
    function renderIngredientes(ingredientes) {
        const container = $('#ingredientes-container');
        if (!ingredientes || ingredientes.length === 0) {
            container.html('<div class="no-results"><p>No se encontraron ingredientes con los filtros seleccionados.</p></div>');
            return;
        }
        
        let html = '<div class="ingredientes-grid">';
        ingredientes.forEach(ingrediente => {
            html += `
                <article class="ingrediente-card" data-id="${ingrediente.id_ingrediente}">
                    <div class="ingrediente-card-image">
                        <img src="${BASE_URL}/resources/img/ingredients/${escapeHtml(ingrediente.imagen)}" 
                             alt="${escapeHtml(ingrediente.nombre)}"
                             onerror="this.src='${BASE_URL}/resources/img/ingredients/placeholder.jpg'">
                    </div>
                    <div class="ingrediente-card-content">
                        <h2 class="ingrediente-title">${escapeHtml(ingrediente.nombre)}</h2>
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
    
    // Abrir modal de ingrediente
    $(document).on('click', '.view-ingrediente', function() {
        const ingredienteId = $(this).data('id');
        openIngredienteModal(ingredienteId);
    });

    // Obtener información Json
    function openIngredienteModal(ingredienteId) {
        $modal.fadeIn(200);
        $('#modal-ingrediente-content').hide();
        $('.modal-loader').show();

        $.ajax({
            url: 'index.php?action=obtener_ingrediente',
            method: 'GET',
            data: { id: ingredienteId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderIngredienteDetail(response.ingrediente);
                } else {
                    $('#modal-ingrediente-content').html(`<div class="error-message"><p>${escapeHtml(response.message || 'Error al cargar el ingrediente')}</p></div>`);
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
        let localizacionesHtml = '<div class="detail-section"><h3>📍 LOCALIZACIONES</h3><ul class="localizaciones-list">';
        if (ingrediente.localizaciones && ingrediente.localizaciones.length > 0) {
            ingrediente.localizaciones.forEach(localizacion => {
                localizacionesHtml += `
                    <li class="localizacion-item">
                        <img src="${BASE_URL}/resources/img/localizaciones/${escapeHtml(localizacion.imagen)}" 
                             alt="${escapeHtml(localizacion.nombre)}"
                             class="localizacion-mini-img"
                             onerror="this.src='${BASE_URL}/resources/img/localizaciones/placeholder.jpg'">
                        <span class="localizacion-nombre">
                            <a href="#" class="view-localizacion" data-id="${localizacion.id_localizacion}">${escapeHtml(localizacion.nombre)}</a>
                        </span>
                        <span class="localizacion-descripcion">x ${localizacion.descripcion}</span>
                    </li>
                `;
            });
        }
        const html = `
            <div class="ingrediente-detail">
                <div class="detail-header">
                    <div class="detail-image">
                        <img src="${BASE_URL}/resources/img/ingredients/${escapeHtml(ingrediente.imagen)}" 
                        alt="${escapeHtml(ingrediente.nombre)}">
                    </div>
                    <div class="detail-info">
                        <h1 class="detail-title">${escapeHtml(ingrediente.nombre)}</h1>
                        <p class="detail-description">${escapeHtml(ingrediente.descripcion)}</p>
                    </div>
                </div>
                ${localizacionesHtml}
            </div>
        `;
        $('#modal-ingrediente-title').text(ingrediente.nombre);
        $('#modal-ingrediente-content').html(html);
    }
    
    $('.modal-close, .modal-overlay').on('click', function() {
        $modal.fadeOut(200);
        $('#modal-ingrediente-content').empty();
    });
    
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $modal.is(':visible')) {
            $modal.fadeOut(200);
        }
    });

    // Mostrar mensaje de error
    function showError(message) {
        $('#ingredientes-container').html(`<div class="error-message"><p>${escapeHtml(message)}</p></div>`);
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>