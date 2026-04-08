<?php 
$activePage = 'efectos';
$titulo = 'Efectos - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <!-- Breadcrumb -->
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
    
    <!-- Contenido principal -->
    <h1 class="page-title">Efectos de los platos</h1>
    <p class="page-description">Los platos cocinados en Hyrule pueden tener efectos especiales que te ayudarán en tu aventura.</p>
    
    <div id="efectos-container">
    <div class="efectos-grid">
        <?php if (!empty($efectos)): ?>
            <?php foreach ($efectos as $efecto): ?>
                <article class="efecto-card">
                    <div class="efecto-card-image">
                        <?php if ($efecto->getIdEfecto() != 12): ?>
                            <img src="<?= BASE_URL ?>/resources/img/effects/<?= $efecto->getImagen() ?>" 
                                alt="<?= htmlspecialchars($efecto->getTipoEfecto()->getNombre()) ?>">
                        <?php else: ?>
                            <p class="alt-efecto">Sin efecto</p>
                        <?php endif; ?>
                    </div>
                    <div class="efecto-card-content">
                        <h2 class="efecto-title"><?= htmlspecialchars($efecto->getTipoEfecto()->getNombre()) ?></h2>
                        <p class="efecto-description"><?= htmlspecialchars($efecto->getDescripcion()) ?></p>
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
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>