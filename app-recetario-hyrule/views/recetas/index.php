<!-- views/recetas/index.php -->
    <!-- Header compartido -->
    <?php 
    // Definir página activa para el header
    $activePage = 'recetas';
    include __DIR__ . '/../layout/header.php';
    ?>
    
    <main class="main-content">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Ruta de navegación">
                <span>Recetas</span> > 
                <span class="active">Todas las recetas</span>
            </nav>
            
            <div class="page-layout">
                <!-- Barra lateral de filtros -->
                <aside class="filters-sidebar">
                    <h2 class="filters-title">Filtros</h2>
                    
                    <!-- Filtro por Efectos -->
                    <div class="filter-group">
                        <h3 class="filter-category">Efectos</h3>
                        <ul class="filter-list" id="filter-efectos">
                            <?php if (!empty($tiposEfectos)): ?>
                                <?php foreach ($tiposEfectos as $tipoEfecto): ?>
                                    <li class="filter-subcategory" data-tipo="efecto" data-id="<?= $tipoEfecto->getIdTipoEfecto() ?>">
                                        <label>
                                            <input type="checkbox" class="filter-checkbox efecto-filter" value="<?= $tipoEfecto->getIdTipoEfecto() ?>">
                                            <?= htmlspecialchars($tipoEfecto->getNombre()) ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <!-- Filtro por Ingredientes (con subcategorías expandibles) -->
                    <div class="filter-group">
                        <h3 class="filter-category">Ingredientes</h3>
                        <ul class="filter-list" id="filter-ingredientes">
                            <!-- Setas -->
                            <li class="filter-category-level" data-categoria="setas">
                                <span class="category-toggle">▶</span>
                                <span class="category-name">Setas</span>
                                <ul class="subcategory-list" style="display: none;">
                                    <?php if (!empty($ingredientesPorCategoria['setas'])): ?>
                                        <?php foreach ($ingredientesPorCategoria['setas'] as $ingrediente): ?>
                                            <li class="filter-subcategory-ingrediente" data-id="<?= $ingrediente->getIdIngrediente() ?>">
                                                <label>
                                                    <input type="checkbox" class="filter-checkbox ingrediente-filter" value="<?= $ingrediente->getIdIngrediente() ?>">
                                                    <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            
                            <!-- Pescados y Mariscos -->
                            <li class="filter-category-level" data-categoria="pescados">
                                <span class="category-toggle">▶</span>
                                <span class="category-name">Pescados y Mariscos</span>
                                <ul class="subcategory-list" style="display: none;">
                                    <?php if (!empty($ingredientesPorCategoria['pescados_mariscos'])): ?>
                                        <?php foreach ($ingredientesPorCategoria['pescados_mariscos'] as $ingrediente): ?>
                                            <li class="filter-subcategory-ingrediente" data-id="<?= $ingrediente->getIdIngrediente() ?>">
                                                <label>
                                                    <input type="checkbox" class="filter-checkbox ingrediente-filter" value="<?= $ingrediente->getIdIngrediente() ?>">
                                                    <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            
                            <!-- Vegetales, Flores y Frutas especiales -->
                            <li class="filter-category-level" data-categoria="vegetales">
                                <span class="category-toggle">▶</span>
                                <span class="category-name">Vegetales, Flores y Frutas especiales</span>
                                <ul class="subcategory-list" style="display: none;">
                                    <?php if (!empty($ingredientesPorCategoria['vegetales_flores_frutas'])): ?>
                                        <?php foreach ($ingredientesPorCategoria['vegetales_flores_frutas'] as $ingrediente): ?>
                                            <li class="filter-subcategory-ingrediente" data-id="<?= $ingrediente->getIdIngrediente() ?>">
                                                <label>
                                                    <input type="checkbox" class="filter-checkbox ingrediente-filter" value="<?= $ingrediente->getIdIngrediente() ?>">
                                                    <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            
                            <!-- Insectos, Reptiles y Partes de Monstruo -->
                            <li class="filter-category-level" data-categoria="insectos">
                                <span class="category-toggle">▶</span>
                                <span class="category-name">Insectos, Reptiles y Partes de Monstruo</span>
                                <ul class="subcategory-list" style="display: none;">
                                    <?php if (!empty($ingredientesPorCategoria['insectos_reptiles_monstruo'])): ?>
                                        <?php foreach ($ingredientesPorCategoria['insectos_reptiles_monstruo'] as $ingrediente): ?>
                                            <li class="filter-subcategory-ingrediente" data-id="<?= $ingrediente->getIdIngrediente() ?>">
                                                <label>
                                                    <input type="checkbox" class="filter-checkbox ingrediente-filter" value="<?= $ingrediente->getIdIngrediente() ?>">
                                                    <?= htmlspecialchars($ingrediente->getNombre()) ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="filter-actions">
                        <button id="apply-filters" class="btn btn-primary">Aplicar filtros</button>
                        <button id="clear-filters" class="btn btn-secondary">Limpiar filtros</button>
                    </div>
                </aside>
                
                <!-- Contenido principal: listado de recetas -->
                <div class="recipes-content">
                    <h1 class="page-title">Todas las recetas</h1>
                    
                    <!-- Loader (oculto inicialmente) -->
                    <div id="loader" class="loader" style="display: none;">
                        <div class="spinner"></div>
                        <p>Cargando recetas...</p>
                    </div>
                    
                    <!-- Contenedor de recetas -->
                    <div id="recipes-container">
                        <?php if (!empty($recetas)): ?>
                            <div class="recipes-grid">
                                <?php foreach ($recetas as $receta): ?>
                                    <article class="recipe-card" data-id="<?= $receta->getIdReceta() ?>">
                                        <div class="recipe-card-image">
                                            <img src="/recetario-hyrule/resources/img/recetas/<?= htmlspecialchars($receta->getImagen()) ?>" 
                                                 alt="<?= htmlspecialchars($receta->getNombre()) ?>"
                                                 onerror="this.src='/recetario-hyrule/resources/img/recetas/placeholder.jpg'">
                                        </div>
                                        <div class="recipe-card-content">
                                            <h2 class="recipe-title"><?= htmlspecialchars($receta->getNombre()) ?></h2>
                                            <div class="recipe-icons">
                                                <?php 
                                                // Mostrar iconos según el tipo de ingredientes principales
                                                $iconos = ['🍗', '🍴'];
                                                foreach ($iconos as $icono): 
                                                ?>
                                                    <span class="recipe-icon"><?= $icono ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <p class="recipe-description"><?= htmlspecialchars($receta->getDescripcion()) ?></p>
                                            <button class="btn btn-link view-recipe" data-id="<?= $receta->getIdReceta() ?>">Ver Receta</button>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-results">
                                <p>No se encontraron recetas con los filtros seleccionados.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal de detalle de receta (oculto inicialmente) -->
    <div id="recipe-modal" class="modal" style="display: none;">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h2 id="modal-title">Receta</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="modal-loader" style="display: none;">
                    <div class="spinner"></div>
                    <p>Cargando detalles...</p>
                </div>
                <div id="modal-content">
                    <!-- Contenido cargado vía AJAX -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer compartido -->
    <?php include __DIR__ . '/../layout/footer.php'; ?>
    
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
</body>
</html>