<?php 
$activePage = 'efectos';
$titulo = 'Efectos - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <nav class="breadcrumb" aria-label="Ruta de navegación">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="?action=efectos">Efectos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Todos los efectos</li>
        </ol>
    </nav>
    <!-- Botón volver atrás -->
    <button id="back-button" class="btn-back" style="display: none;" aria-label="Volver atrás">
        ← Volver
    </button>

    <!-- Barra buscador -->
    <div class="search-bar-container">
        <input type="text" id="search-input" class="search-input-large" 
            placeholder="🔍 Buscar efectos..." aria-label="Buscar efectos por nombre">
    </div>
    
    <h1 class="page-title">Efectos de los platos</h1>
    <p class="page-description">Los platos cocinados en Hyrule pueden tener efectos especiales que te ayudarán en tu aventura.</p>
    
    <div class="efectos-grid">
        <?php if (!empty($efectos)): ?>
            <?php foreach ($efectos as $efecto): ?>
                <article class="efecto-card">
                    <div class="efecto-card-image">
                        <img src="<?= BASE_URL ?>/resources/img/effects/<?= strtolower($efecto->getTipoEfecto()->getNombre()) ?>.png" 
                             alt="<?= htmlspecialchars($efecto->getTipoEfecto()->getNombre()) ?>"
                             onerror="this.src='<?= BASE_URL ?>/resources/img/effects/default.png'">
                    </div>
                    <div class="efecto-card-content">
                        <h2 class="efecto-title"><?= htmlspecialchars($efecto->getTipoEfecto()->getNombre()) ?></h2>
                        <p class="efecto-description"><?= htmlspecialchars($efecto->getDescripcion()) ?></p>
                        <a href="?action=obtener_efecto&id=<?= $efecto->getIdEfecto() ?>" class="btn btn-link">
                            Ver recetas con este efecto →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results" role="status">
                <p>No se encontraron efectos.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>