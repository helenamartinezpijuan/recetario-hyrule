<!-- INCLUIR HEADER -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Título específico de la página -->
<?php $titulo = 'Lo sentimos, ha ocurrido un error'; ?>

<div class="error-container">
    <p class="mensaje-error"><?php echo htmlspecialchars($mensaje); ?></p>

    <?php if (isset($detalle) && APP_ENV === 'development'): ?>
        <div class="detalle-error">
            <h3>Detalle técnico:</h3>
            <pre><?php echo htmlspecialchars($detalle); ?></pre>
        </div>
    <?php endif; ?>
</div>

<div class="acciones">
    <a href="javascript:history.back()">Intentar de nuevo</a> |
    <a href="<?php echo $base_url; ?>/public/index.php?action=mostrarFormularioPrincipal">Volver al inicio</a>
</div>

<!-- INCLUIR FOOTER -->
<?php require_once __DIR__ . '/../layout/footer.php'; ?>