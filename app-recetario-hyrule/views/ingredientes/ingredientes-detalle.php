<?php 
$activePage = 'ingredientes';
$titulo = $ingrediente->getNombre() . ' - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <nav class="breadcrumb" aria-label="Ruta de navegación">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="?action=ingredientes">Ingredientes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($ingrediente->getNombre()) ?></li>
        </ol>
    </nav>
    
    <div class="detail-page">
        <div class="detail-header">
            <div class="detail-image">
                <img src="<?= BASE_URL ?>/resources/img/ingredients/<?= htmlspecialchars($ingrediente->getImagen()) ?>" 
                     alt="<?= htmlspecialchars($ingrediente->getNombre()) ?>">
            </div>
            <div class="detail-info">
                <h1 class="detail-title"><?= htmlspecialchars($ingrediente->getNombre()) ?></h1>
                <p class="detail-description"><?= htmlspecialchars($ingrediente->getDescripcion()) ?></p>
            </div>
        </div>
        
        <!-- Localizaciones donde se encuentra -->
        <div class="detail-section">
            <h2 class="section-title">📍 Localizaciones</h2>
            <?php if (!empty($localizaciones)): ?>
                <div class="localizaciones-grid">
                    <?php foreach ($localizaciones as $localizacion): ?>
                        <div class="localizacion-mini-card">
                            <div class="localizacion-mini-image">
                                <img src="<?= BASE_URL ?>/resources/img/locations/<?= strtolower(str_replace(' ', '-', $localizacion->getRegion())) ?>/<?= htmlspecialchars($localizacion->getImagen()) ?>" 
                                     alt="<?= htmlspecialchars($localizacion->getNombre()) ?>">
                            </div>
                            <div class="localizacion-mini-info">
                                <h3><?= htmlspecialchars($localizacion->getNombre()) ?></h3>
                                <span class="region-badge"><?= htmlspecialchars($localizacion->getRegion()) ?></span>
                                <a href="?action=obtener_localizacion&id=<?= $localizacion->getIdLocalizacion() ?>" class="btn-link-small">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-results">Este ingrediente se encuentra en todo Hyrule.</p>
            <?php endif; ?>
        </div>
        
        <div class="detail-actions">
            <a href="?action=ingredientes" class="btn btn-secondary">← Volver a ingredientes</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>