<?php 
$activePage = 'home';
$titulo = 'Inicio - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<section class="hero">
    <div class="container hero-content">
        <h1 class="hero-title">RECETARIO DE HYRULE</h1>
        <p class="hero-description">
            Descubre todas las recetas, ingredientes y secretos culinarios de 
            <strong>Zelda: Breath of the Wild</strong>. Aprende a cocinar los platos 
            más deliciosos que te ayudarán en tu aventura por Hyrule.
        </p>
        <div class="hero-buttons">
            <a href="?action=recetas" class="btn btn-primary btn-hero">Explorar recetas</a>
            <a href="?action=ingredientes" class="btn btn-secondary btn-hero">Ver ingredientes</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">¿Qué encontrarás aquí?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🍲</div>
                <h3>Más de 100 recetas</h3>
                <p>Descubre todas las combinaciones de ingredientes para crear platos únicos.</p>
                <a href="?action=recetas" class="feature-link">Ver recetas →</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🥬</div>
                <h3>Ingredientes de Hyrule</h3>
                <p>Conoce las propiedades de cada ingrediente y dónde encontrarlos.</p>
                <a href="?action=ingredientes" class="feature-link">Ver ingredientes →</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Efectos especiales</h3>
                <p>Aprende qué efectos tienen los platos y cómo aprovecharlos.</p>
                <a href="?action=efectos" class="feature-link">Ver efectos →</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🗺️</div>
                <h3>Localizaciones</h3>
                <p>Explora las regiones de Hyrule y descubre dónde encontrar ingredientes.</p>
                <a href="?action=localizaciones" class="feature-link">Ver localizaciones →</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>