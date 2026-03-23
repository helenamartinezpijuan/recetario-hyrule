<!-- INCLUIR HEADER -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Título específico de la página -->
<?php $titulo = "¡Cliente creado exitosamente!"; ?>

<div class="datos-cliente">
    <h2>Datos del nuevo cliente:</h2>
    <ul>
        <li><strong>ID:</strong> <?php echo $cliente->getIdcliente(); ?></li>
        <li><strong>DNI:</strong> <?php echo htmlspecialchars($cliente->getDni()); ?></li>
        <li><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente->getNombre()); ?></li>
        <li><strong>Dirección:</strong> <?php echo htmlspecialchars($cliente->getDireccionPostal()); ?></li>
        <li><strong>Nº Cuenta:</strong> <?php echo htmlspecialchars($cliente->getNumCuenta()); ?></li>
    </ul>
</div>

<?php if (isset($mensaje)): ?>
    <p class="mensaje"><?php echo $mensaje; ?></p>
<?php endif; ?>

<div class="acciones">
    <a href="<?php echo $base_url; ?>/public/index.php?action=mostrarFormularioPrincipal">Volver al formulario</a>
</div>

<!-- INCLUIR FOOTER -->
<?php require_once __DIR__ . '/../layout/footer.php'; ?>