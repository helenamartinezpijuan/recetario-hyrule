<?php $titulo = 'Resultado de la búsqueda de clientes'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (empty($clientes)): ?>
    <!-- Caso 1: No hay resultados -->
    <div class="mensaje-info">
        <p>No se encontraron clientes con los criterios de búsqueda.</p>
    </div>
<?php else: ?>
    <!-- Caso 2: Hay resultados -->
    <div class="resultados">
        <p>Se encontraron <strong><?php echo count($clientes); ?></strong> cliente(s):</p>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección Postal</th>
                    <th>Número de Cuenta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente->getIdcliente(); ?></td>
                        <td><?php echo htmlspecialchars($cliente->getDni()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->getDireccionPostal()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->getNumCuenta()); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="acciones">
    <a href="<?php echo $base_url; ?>/public/index.php?action=mostrarFormularioPrincipal">Volver al formulario</a>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>