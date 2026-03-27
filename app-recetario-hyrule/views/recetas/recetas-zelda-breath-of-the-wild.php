<!-- Header compartido -->
<?php 
$activePage = 'recetas';
$titulo = 'Recetas';
include __DIR__ . '/../layout/header.php';
?>

<main class="main-content">
    <div class="container">
        <!-- Breadcrumb con aria-label para lectores de pantalla -->
        <nav class="breadcrumb" aria-label="Ruta de navegación">
            <ol class="breadcrumb-list" style="list-style: none; padding: 0; margin: 0;">
                <li class="breadcrumb-item">
                    <a href="?action=recetas">Recetas</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Todas las recetas
                </li>
            </ol>
        </nav>
        
        <div class="page-layout">
            <!-- Barra lateral de filtros con role complementario -->
            <aside class="filters-sidebar" aria-label="Filtros de búsqueda">
                <h2 class="filters-title">Filtros</h2>
                
                <!-- Filtro por Efectos -->
                <div class="filter-group">
                    <h3 class="filter-category" id="filter-efectos-heading">Efectos</h3>
                    <ul class="filter-list" aria-labelledby="filter-efectos-heading">
                        <?php if (!empty($tiposEfectos)): ?>
                            <?php foreach ($tiposEfectos as $tipoEfecto): ?>
                                <li class="filter-subcategory">
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               class="filter-checkbox efecto-filter" 
                                               value="<?= $tipoEfecto->getIdTipoEfecto() ?>"
                                               aria-label="Filtrar por efecto <?= htmlspecialchars($tipoEfecto->getNombre()) ?>">
                                        <?= htmlspecialchars($tipoEfecto->getNombre()) ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Filtro por Ingredientes -->
                <div class="filter-group">
                    <h3 class="filter-category" id="filter-ingredientes-heading">Ingredientes</h3>
                    <ul class="filter-list" aria-labelledby="filter-ingredientes-heading">
                        <!-- Setas -->
                        <li class="filter-category-level">
                            <button class="category-toggle" 
                                    aria-expanded="false" 
                                    aria-controls="setas-subcategory"
                                    aria-label="Expandir categoría Setas">
                                ▶
                            </button>
                            <span class="category-name">Setas</span>
                            <ul class="subcategory-list" id="setas-subcategory" style="display: none;">
                                <?php if (!empty($ingredientesPorCategoria['setas'])): ?>
                                    <?php foreach ($ingredientesPorCategoria['setas'] as $ingrediente): ?>
                                        <li>
                                            <label class="checkbox-label">
                                                <input type="checkbox" 
                                                       class="filter-checkbox ingrediente-filter" 
                                                       value="<?= $ingrediente->getIdIngrediente() ?>"
                                                       aria-label="Filtrar por ingrediente <?= htmlspecialchars($ingrediente->getNombre()) ?>">
                                                <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <!-- ... resto de categorías con misma estructura ... -->
                    </ul>
                </div>
                
                <div class="filter-actions">
                    <button id="apply-filters" class="btn btn-primary" aria-label="Aplicar filtros seleccionados">
                        Aplicar filtros
                    </button>
                    <button id="clear-filters" class="btn btn-secondary" aria-label="Limpiar todos los filtros">
                        Limpiar filtros
                    </button>
                </div>
            </aside>
            
            <!-- Contenido principal -->
            <div class="recipes-content">
                <h1 class="page-title">Todas las recetas</h1>
                
                <!-- Loader con aria-live para anunciar cambios -->
                <div id="loader" class="loader" style="display: none;" aria-live="polite">
                    <div class="spinner"></div>
                    <p>Cargando recetas...</p>
                </div>
                
                <!-- Contenedor de recetas con aria-live para actualizaciones dinámicas -->
                <div id="recipes-container" aria-live="polite">
                    <?php if (!empty($recetas)): ?>
                        <div class="recipes-grid" role="list">
                            <?php foreach ($recetas as $receta): ?>
                                <article class="recipe-card" data-id="<?= $receta->getIdReceta() ?>" role="listitem">
                                    <div class="recipe-card-image">
                                        <img src="<?= BASE_URL ?>/resources/img/recetas/<?= htmlspecialchars($receta->getImagen()) ?>" 
                                             alt="<?= htmlspecialchars($receta->getNombre()) ?>"
                                             loading="lazy">
                                    </div>
                                    <div class="recipe-card-content">
                                        <h2 class="recipe-title"><?= htmlspecialchars($receta->getNombre()) ?></h2>
                                        <div class="recipe-icons" aria-hidden="true">
                                            <span class="recipe-icon">🍗</span>
                                            <span class="recipe-icon">🍴</span>
                                        </div>
                                        <p class="recipe-description"><?= htmlspecialchars($receta->getDescripcion()) ?></p>
                                        <button class="btn btn-link view-recipe" 
                                                data-id="<?= $receta->getIdReceta() ?>"
                                                aria-label="Ver detalles de <?= htmlspecialchars($receta->getNombre()) ?>">
                                            Ver Receta
                                        </button>
                                    </div>
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
</main>

<!-- Modal con atributos ARIA para accesibilidad -->
<div id="recipe-modal" class="modal" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-title"
     style="display: none;">
    <div class="modal-overlay" aria-hidden="true"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modal-title">Receta</h2>
            <button class="modal-close" aria-label="Cerrar modal">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
            <div class="modal-loader" style="display: none;">
                <div class="spinner"></div>
                <p>Cargando detalles...</p>
            </div>
            <div id="modal-content"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ==================== MANEJO DE FILTROS ====================
        
        // Expandir/colapsar categorías de ingredientes
        $('.filter-category-level .category-toggle').on('click', function(e) {
            e.stopPropagation();
            const $toggle = $(this);
            const $subList = $toggle.closest('.filter-category-level').find('.subcategory-list');
            
            $subList.slideToggle(200);
            $toggle.text($subList.is(':visible') ? '▼' : '▶');
        });
        
        // Aplicar filtros
        $('#apply-filters').on('click', function() {
            const efectosSeleccionados = [];
            const ingredientesSeleccionados = [];
            
            // Recoger efectos seleccionados
            $('.efecto-filter:checked').each(function() {
                efectosSeleccionados.push($(this).val());
            });
            
            // Recoger ingredientes seleccionados
            $('.ingrediente-filter:checked').each(function() {
                ingredientesSeleccionados.push($(this).val());
            });
            
            // Mostrar loader
            $('#loader').show();
            $('#recipes-container').fadeOut(200);
            
            // Enviar petición AJAX
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
                        renderRecipes(response.recetas);
                    } else {
                        showError(response.message || 'Error al cargar las recetas');
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
        });
        
        // Limpiar filtros
        $('#clear-filters').on('click', function() {
            $('.filter-checkbox').prop('checked', false);
            $('#apply-filters').trigger('click');
        });
        
        // Función para renderizar recetas desde JSON
        function renderRecipes(recetas) {
            const container = $('#recipes-container');
            if (!recetas || recetas.length === 0) {
                container.html('<div class="no-results"><p>No se encontraron recetas con los filtros seleccionados.</p></div>');
                return;
            }
            
            let html = '<div class="recipes-grid">';
            recetas.forEach(receta => {
                html += `
                    <article class="recipe-card" data-id="${receta.id_receta}">
                        <div class="recipe-card-image">
                            <img src="/recetario-hyrule/resources/img/recetas/${escapeHtml(receta.imagen)}" 
                                    alt="${escapeHtml(receta.nombre)}"
                                    onerror="this.src='/recetario-hyrule/resources/img/recetas/placeholder.jpg'">
                        </div>
                        <div class="recipe-card-content">
                            <h2 class="recipe-title">${escapeHtml(receta.nombre)}</h2>
                            <div class="recipe-icons">
                                <span class="recipe-icon">🍗</span>
                                <span class="recipe-icon">🍴</span>
                            </div>
                            <p class="recipe-description">${escapeHtml(receta.descripcion)}</p>
                            <button class="btn btn-link view-recipe" data-id="${receta.id_receta}">Ver Receta</button>
                        </div>
                    </article>
                `;
            });
            html += '</div>';
            container.html(html);
        }
        
        // ==================== MODAL DE DETALLE DE RECETA ====================
        
        const $modal = $('#recipe-modal');
        
        // Abrir modal al hacer clic en "Ver Receta"
        $(document).on('click', '.view-recipe', function() {
            const recetaId = $(this).data('id');
            openRecipeModal(recetaId);
        });
        
        function openRecipeModal(recetaId) {
            // Mostrar modal y loader
            $modal.fadeIn(200);
            $('#modal-content').hide();
            $('.modal-loader').show();
            
            // Cargar detalles vía AJAX
            $.ajax({
                url: 'index.php?action=obtener_receta',
                method: 'GET',
                data: { id: recetaId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderRecipeDetail(response.receta);
                    } else {
                        $('#modal-content').html(`
                            <div class="error-message">
                                <p>${escapeHtml(response.message || 'Error al cargar la receta')}</p>
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#modal-content').html(`
                        <div class="error-message">
                            <p>Error de conexión. Por favor, intenta de nuevo.</p>
                        </div>
                    `);
                },
                complete: function() {
                    $('.modal-loader').hide();
                    $('#modal-content').fadeIn(200);
                }
            });
        }
        
        function renderRecipeDetail(receta) {
            // Renderizar efectos
            let efectosHtml = '';
            if (receta.efectos && receta.efectos.length > 0) {
                efectosHtml = '<div class="detail-section"><h3>EFECTOS</h3><ul class="efectos-list">';
                receta.efectos.forEach(efecto => {
                    efectosHtml += `<li>${escapeHtml(efecto.nombre)}: ${escapeHtml(efecto.descripcion)}</li>`;
                });
                efectosHtml += '</ul></div>';
            } else {
                efectosHtml = '<div class="detail-section"><h3>EFECTOS</h3><p>Sin efecto</p></div>';
            }
            
            // Renderizar ingredientes
            let ingredientesHtml = '<div class="detail-section"><h3>INGREDIENTES</h3><ul class="ingredientes-list">';
            if (receta.ingredientes && receta.ingredientes.length > 0) {
                receta.ingredientes.forEach(ing => {
                    ingredientesHtml += `
                        <li class="ingrediente-item">
                            <span class="ingrediente-nombre">${escapeHtml(ing.nombre)}</span>
                            <span class="ingrediente-cantidad">x ${ing.cantidad}</span>
                        </li>
                    `;
                });
            }
            ingredientesHtml += '</ul></div>';
            
            const html = `
                <div class="recipe-detail">
                    <div class="detail-header">
                        <div class="detail-image">
                            <img src="/recetario-hyrule/resources/img/recetas/${escapeHtml(receta.imagen)}" 
                                    alt="${escapeHtml(receta.nombre)}"
                                    onerror="this.src='/recetario-hyrule/resources/img/recetas/placeholder.jpg'">
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
            
            $('#modal-title').text(receta.nombre);
            $('#modal-content').html(html);
        }
        
        // Cerrar modal
        $('.modal-close, .modal-overlay').on('click', function() {
            $modal.fadeOut(200);
            $('#modal-content').empty();
        });
        
        // Cerrar con tecla ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $modal.is(':visible')) {
                $modal.fadeOut(200);
            }
        });
        
        // Helper: escape HTML para prevenir XSS
        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
        
        function showError(message) {
            $('#recipes-container').html(`
                <div class="error-message">
                    <p>${escapeHtml(message)}</p>
                </div>
            `);
        }
    });
</script>

<!-- Footer compartido -->
<?php include __DIR__ . '/../layout/footer.php'; ?>