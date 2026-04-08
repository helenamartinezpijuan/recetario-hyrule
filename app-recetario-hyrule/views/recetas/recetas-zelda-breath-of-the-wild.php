<?php 
$activePage = 'recetas';
$titulo = 'Recetas - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Ruta de navegación">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="?action=recetas">Recetas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Todas las recetas</li>
        </ol>
    </nav>
    <!-- Botón volver atrás -->
    <button id="back-button" class="btn-back" style="display: none;" aria-label="Volver atrás">
        ← Volver
    </button>

    <!-- Barra buscador -->
    <div class="search-bar-container">
        <input type="text" id="search-input" class="search-input-large" 
            placeholder="🔍 Buscar recetas..." aria-label="Buscar recetas por nombre">
    </div>
    
    <div class="page-layout">
        <!-- Barra lateral de filtros -->
        <aside class="filters-sidebar" aria-label="Filtros de búsqueda">
            <h2 class="filters-title">Filtros</h2>
            
            <!-- Filtro por efectos -->
            <div class="filter-group">
                <h3 class="filter-category" id="filter-efectos-heading">Efectos</h3>
                <ul class="filter-list" aria-labelledby="filter-efectos-heading">
                    <?php if (!empty($tipos_efectos)): ?>
                        <?php foreach ($tipos_efectos as $tipo_efecto): ?>
                            <li>
                                <label class="checkbox-label">
                                    <input type="checkbox" class="filter-checkbox efecto-filter" 
                                            value="<?= $tipo_efecto->getIdTipoEfecto() ?>"
                                            aria-label="Filtrar por efecto <?= htmlspecialchars($tipo_efecto->getNombre()) ?>">
                                    <?= htmlspecialchars($tipo_efecto->getNombre()) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Filtro por ingredientes -->
            <div class="filter-group">
                <h3 class="filter-category" id="filter-ingredientes-heading">Ingredientes</h3>
                <ul class="filter-list" aria-labelledby="filter-ingredientes-heading">
                    <?php foreach ($ingredientes_por_categoria as $categoria => $ingredientes): ?>
                        <li class="filter-category-level">
                            <button class="category-toggle" aria-expanded="false" 
                                    aria-controls="<?= $categoria ?>-subcategory"
                                    aria-label="Expandir categoría <?= ucfirst(str_replace('_', ' ', $categoria)) ?>">
                                ▶
                            </button>
                            <span class="category-name"><?= ucfirst(str_replace('_', ' ', $categoria)) ?></span>
                            <ul class="subcategory-list" id="<?= $categoria ?>-subcategory" style="display: none;">
                                <?php if (!empty($ingredientes)): ?>
                                    <?php foreach ($ingredientes as $ingrediente): ?>
                                        <li>
                                            <label class="checkbox-label">
                                                <input type="checkbox" class="filter-checkbox ingrediente-filter" 
                                                        value="<?= $ingrediente->getIdIngrediente() ?>"
                                                        aria-label="Filtrar por ingrediente <?= htmlspecialchars($ingrediente->getNombre()) ?>">
                                                <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="filter-actions">
                <button id="apply-filters" class="btn btn-primary">Aplicar filtros</button>
                <button id="clear-filters" class="btn btn-secondary">Limpiar filtros</button>
            </div>
        </aside>
        
        <!-- Contenido principal -->
        <div class="recetas-content">
            <h1 class="page-title">Todas las recetas</h1>
            
            <div id="loader" class="loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando recetas...</p>
            </div>
            
            <div id="recetas-container">
                <?php if (!empty($recetas_detalles)): ?>
                    <div class="recetas-grid" role="list">
                        <?php foreach ($recetas_detalles as $receta): ?>
                            <article class="receta-card" data-id="<?= $receta['id_receta'] ?>">
                                <!---------------------------------------------------------------------------------------------------------->
                                <div class="receta-card-image">
                                    <img src="<?= BASE_URL ?>/resources/img/recipes/<?= htmlspecialchars($receta['imagen']) ?>" 
                                            alt="Receta: <?= htmlspecialchars($receta['nombre']) ?>"
                                            loading="lazy">
                                </div>
                                <!---------------------------------------------------------------------------------------------------------->
                                <div class="receta-card-content">
                                    <h2 class="receta-title"><?= htmlspecialchars($receta['nombre']) ?></h2>
                                    <div class="receta-icons" aria-hidden="true">
                                        <?php if (!empty($receta['efectos'])): ?>
                                            <?php foreach ($receta['efectos'] as $efecto): ?>
                                                <?php if ($efecto['id_efecto'] != 12): ?>
                                                    <img src="<?= BASE_URL ?>/resources/img/effects/<?= htmlspecialchars($efecto['imagen']) ?>"
                                                                alt="<?= htmlspecialchars($efecto['nombre']) ?>"  
                                                                class='efecto-icon-mini'>
                                                <?php else: ?>
                                                    <p class="alt-efecto">Sin efecto</p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="receta-icon">🍗</span>
                                            <span class="receta-icon">🍴</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="receta-description"><?= htmlspecialchars($receta['descripcion']) ?></p>
                                    <button class="btn btn-link view-receta" 
                                            data-id="<?= $receta['id_receta'] ?>"
                                            aria-label="Ver detalles de <?= htmlspecialchars($receta['nombre']) ?>">
                                        Ver Receta
                                    </button>
                                </div>
                                <!---------------------------------------------------------------------------------------------------------->

                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results" role="status">
                        <p>No se encontraron recetas con los filtros seleccionados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalle de receta -->
<div id="receta-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-receta-title" style="display: none;">
    <div class="modal-overlay" aria-hidden="true"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modal-receta-title">Receta</h2>
            <button class="modal-close" aria-label="Cerrar modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando detalles...</p>
            </div>
            <div id="modal-receta-content"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const BASE_URL = '<?= BASE_URL ?>';
    const $modal = $('#receta-modal');

    // Expandir/colapsar categorías
    $('.filter-category-level .category-toggle').on('click', function(e) {
        e.stopPropagation();
        const $toggle = $(this);
        const $subList = $toggle.closest('.filter-category-level').find('.subcategory-list');
        const isExpanded = $subList.is(':visible');
        
        $subList.slideToggle(200);
        $toggle.text(isExpanded ? '▶' : '▼');
        $toggle.attr('aria-expanded', !isExpanded);
    });
    
    // Aplicar filtros
    $('#apply-filters').on('click', function() {
        const efectosSeleccionados = [];
        const ingredientesSeleccionados = [];
        
        $('.efecto-filter:checked').each(function() {
            efectosSeleccionados.push($(this).val());
        });
        
        $('.ingrediente-filter:checked').each(function() {
            ingredientesSeleccionados.push($(this).val());
        });
        
        $('#loader').show();
        $('#recetas-container').fadeOut(200);
        
        $.ajax({
            url: 'index.php?action=filtrar_recetas',
            method: 'POST',
            data: {
                efectos: efectosSeleccionados,
                ingredientes: ingredientesSeleccionados
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderRecetas(response.recetas);
                } else {
                    showError(response.message || 'Error al cargar las recetas');
                }
            },
            error: function() {
                showError('Error de conexión con el servidor');
            },
            complete: function() {
                $('#loader').hide();
                $('#recetas-container').fadeIn(200);
            }
        });
    });

    // Limpiar filtros
    $('#clear-filters').on('click', function() {
        $('.filter-checkbox').prop('checked', false);
        $('#apply-filters').trigger('click');
    });

    // Búsqueda por nombre
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = $(this).val();
            
            $('#loader').show();
            $('#recetas-container').fadeOut(200);
            
            $.ajax({
                url: 'index.php?action=buscar_recetas',
                method: 'POST',
                data: { nombre: searchTerm },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderRecetas(response.recetas);
                        // Actualizar breadcrumb
                        if (searchTerm) {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=recetas">Recetas</a></li>
                                <li class="breadcrumb-item active">Buscando: ${escapeHtml(searchTerm)}</li>
                            `);
                        } else {
                            $('.breadcrumb-list').html(`
                                <li class="breadcrumb-item"><a href="?action=recetas">Recetas</a></li>
                                <li class="breadcrumb-item active">Todas las recetas</li>
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
                    $('#recetas-container').fadeIn(200);
                }
            });
        }, 300);
    });
    
    // Mostrar recetas filtradas
    function renderRecetas(recetas) {
        const container = $('#recetas-container');
        if (!recetas || recetas.length === 0) {
            container.html('<div class="no-results"><p>No se encontraron recetas con los filtros seleccionados.</p></div>');
            return;
        }
        
        let html = '<div class="recetas-grid">';
        recetas.forEach(receta => {
            // Obtener efectos de la receta
            let efectosHtml = '';
            if (receta.efectos && receta.efectos.length > 0) {
                receta.efectos.slice(0, 3).forEach(efecto => {
                    if (efecto.id_efecto != 12) {
                        efectosHtml += `<img src="${BASE_URL}/resources/img/effects/${efecto.imagen}" 
                                        alt="${efecto.nombre}" 
                                        class="efecto-icon-mini"
                                        title="${efecto.nombre}">`;
                    } else {
                        efectosHtml += `<p class="alt-efecto">Sin efecto</p>`;
                    }
                    
                });
            } else {
                efectosHtml = '<span class="receta-icon">🍽️</span>';
            }

            html += `
                <article class="receta-card" data-id="${receta.id_receta}">
                    <div class="receta-card-image">
                        <img src="${BASE_URL}/resources/img/recipes/${escapeHtml(receta.imagen)}" 
                            alt="${escapeHtml(receta.nombre)}">
                    </div>
                    <div class="receta-card-content">
                        <h2 class="receta-title">${escapeHtml(receta.nombre)}</h2>
                        <div class="receta-icons" aria-hidden="true">
                            ${efectosHtml}
                        </div>
                        <p class="receta-description">${escapeHtml(receta.descripcion)}</p>
                        <button class="btn btn-link view-receta" data-id="${receta.id_receta}">Ver Receta</button>
                    </div>
                </article>
            `;
        });

        html += '</div>';
        container.html(html);
    }

    // Abrir modal de receta
    $(document).on('click', '.view-receta', function() {
        const recetaId = $(this).data('id');
        openRecetaModal(recetaId);
    });
    
    // Obtener información Json
    function openRecetaModal(recetaId) {
        $modal.fadeIn(200);
        $('#modal-receta-content').hide();
        $('.modal-loader').show();
        
        $.ajax({
            url: 'index.php?action=obtener_receta',
            method: 'GET',
            data: { id: recetaId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderRecetaDetail(response.receta);
                } else {
                    $('#modal-receta-content').html(`<div class="error-message"><p>${escapeHtml(response.message || 'Error al cargar la receta')}</p></div>`);
                }
            },
            error: function() {
                $('#modal-receta-content').html('<div class="error-message"><p>Error de conexión</p></div>');
            },
            complete: function() {
                $('.modal-loader').hide();
                $('#modal-receta-content').fadeIn(200);
            }
        });
    }
    
    // Mostrar información de receta en HTML
    function renderRecetaDetail(receta) {
        let efectosHtml = '<div class="detail-section"><h3>EFECTOS</h3><div class="efectos-grid-mini">';
        if (receta.efectos && receta.efectos.length > 0) {
            receta.efectos.forEach(efecto => {
                efectosHtml += `<div class="efecto-mini-card">`;
                if (efecto.id_efecto != 12) {
                    efectosHtml += `<img src="${BASE_URL}/resources/img/effects/${escapeHtml(efecto.imagen)}" 
                                        alt="${escapeHtml(efecto.nombre)}"
                                        class="efecto-mini-img">`;
                }
                efectosHtml += `
                    <div class="efecto-mini-info">
                        <strong>${escapeHtml(efecto.nombre)}</strong>
                        <p>${escapeHtml(efecto.descripcion)}</p>
                    </div>
                </div>`;
            });
        } else {
            efectosHtml += '<p>Sin efecto</p>';
        }
        efectosHtml += '</div></div>';
        
        let ingredientesHtml = '<div class="detail-section"><h3>INGREDIENTES</h3><ul class="ingredientes-list">';
        if (receta.ingredientes && receta.ingredientes.length > 0) {
            receta.ingredientes.forEach(ingrediente => {
                ingredientesHtml += `
                    <li class="ingrediente-item">
                        <img src="${BASE_URL}/resources/img/ingredients/${escapeHtml(ingrediente.imagen)}" 
                             alt="${escapeHtml(ingrediente.nombre)}"
                             class="ingrediente-mini-img">
                        <span class="ingrediente-nombre">
                            ${escapeHtml(ingrediente.nombre)}
                        </span>
                        <span class="ingrediente-cantidad">x ${ingrediente.cantidad}</span>
                    </li>
                `;
            });
        }
        ingredientesHtml += '</ul></div>';
        
        const html = `
            <div class="receta-detail">
                <div class="detail-header">
                    <div class="detail-image">
                        <img src="${BASE_URL}/resources/img/recipes/${escapeHtml(receta.imagen)}" 
                             alt="${escapeHtml(receta.nombre)}">
                    </div>
                    <div class="detail-info">
                        <h1 class="detail-title">${escapeHtml(receta.nombre)}</h1>
                        <p class="detail-description">${escapeHtml(receta.descripcion)}</p>
                    </div>
                </div>
                ${efectosHtml}
                ${ingredientesHtml}
            </div>
        `;
        
        $('#modal-receta-title').text(receta.nombre);
        $('#modal-receta-content').html(html);
    }
    
    // Cerrar modal
    $('.modal-close, .modal-overlay').on('click', function() {
        $modal.fadeOut(200);
        $('#modal-receta-content').empty();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $modal.is(':visible')) {
            $modal.fadeOut(200);
        }
    });
    
    // Mostrar mensaje de error
    function showError(message) {
        $('#recetas-container').html(`<div class="error-message"><p>${escapeHtml(message)}</p></div>`);
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>