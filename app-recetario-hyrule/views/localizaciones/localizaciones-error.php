<?php 
$activePage = 'localizaciones';
$titulo = 'Error - Recetario de Hyrule';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container error-container">
    <div class="error-card">
        <div class="error-icon">🗺️</div>
        <h1 class="error-title">¡Ups! Algo salió mal</h1>
        <p class="error-message"><?= htmlspecialchars($mensaje ?? 'Error al cargar la página de localizaciones') ?></p>
        <div class="error-actions">
            <a href="?action=localizaciones" class="btn btn-primary">Volver a localizaciones</a>
            <a href="?action=home" class="btn btn-secondary">Ir al inicio</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>