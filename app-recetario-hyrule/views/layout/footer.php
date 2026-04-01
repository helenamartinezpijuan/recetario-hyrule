</main>

    <!-- Filtros SVG para daltonismo -->
     <svg style="position: absolute; width: 0; height: 0; visibility: hidden;">
        <filter id="protanopia-filter">
            <feColorMatrix type="matrix" values="0.567,0.433,0,0,0 0.558,0.442,0,0,0 0,0.242,0.758,0,0 0,0,0,1,0"/>
        </filter>
        <filter id="tritanopia-filter">
            <feColorMatrix type="matrix" values="0.967,0.033,0,0,0 0,0.733,0.267,0,0 0,0.183,0.817,0,0 0,0,0,1,0"/>
        </filter>
    </svg>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?= date('Y') ?> Recetario de Hyrule - Zelda: Breath of the Wild</p>
                <p>Este es un proyecto educativo sin fines de lucro. Todos los derechos de Zelda pertenecen a Nintendo.</p>
            </div>
        </div>
    </footer>

    <!-- Widget de accesibilidad (script ya está en header.php) -->
</body>
</html>