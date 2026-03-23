<!-- INCLUIR HEADER -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Título específico de la página -->
<?php $titulo = 'Formulario de Clientes - Clínica Veterinaria'; ?>

<script> 
$(document).ready(function() {
    /* Ocultamos todos los campos hasta que el usuario seleccione una opción */
    $("#buscar-cliente").slideUp();
    $("#crear-cliente").slideUp();
    $("#actualizar-cliente").slideUp();
    $("#eliminar-cliente").slideUp();

    /* Toggle para el desplegable de Buscar Cliente */
    $("#click-buscar-cliente").click(function() {
        $("#buscar-cliente").slideToggle();
    })
    /* Toggle para el desplegable de Crear Cliente */
    $("#click-crear-cliente").click(function() {
        $("#crear-cliente").slideToggle();
    });
    /* Toggle para el desplegable de Actualizar Cliente */
    $("#click-actualizar-cliente").click(function() {
        $("#actualizar-cliente").slideToggle();
    });
    /* Toggle para el desplegable de Eliminar Cliente */
    $("#click-eliminar-cliente").click(function() {
        $("#eliminar-cliente").slideToggle();
    });
});

function soloNumeros(event) {
    /* Leemos el código de la tecla que ha presionado el usuario. */
    var codigo = event.keyCode;
    if (
        (codigo >= 48 && codigo <= 57) ||      // Números arriba
        (codigo >= 96 && codigo <= 105) ||     // Números teclado numérico
        codigo === 8 ||                        // Backspace
        codigo === 9 ||                        // Tab
        codigo === 13 ||                       // Enter
        codigo === 46 ||                       // Delete
        (codigo >= 37 && codigo <= 40)         // Flechas
    ) {
        // Si es un valor numérico o el usuario está editando el texto, permitimos el evento.
        return;
    } else {
        /* En caso contrario, lo cancelamos. */
        event.preventDefault();
    }
}

function soloTextoYNumeros(event) {
    var codigo = event.keyCode;
    var tecla = event.key;
    
    // Teclas de control SIEMPRE permitidas
    var teclasControl = [
        8, 9, 13, 16, 17, 18, 20, 27, 32,       // Control básico + espacio
        37, 38, 39, 40, 46,                     // Flechas + Delete
        33, 34, 35, 36, 45,                     // Page Up/Down, End, Home, Insert
        91, 92, 93,                             // Teclas Windows/Mac
        112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123 // F1-F12
    ];
    
    // Permitir teclas de control
    if (teclasControl.includes(codigo)) {
        return;
    }
    
    // Permitir números
    if ((codigo >= 48 && codigo <= 57) || (codigo >= 96 && codigo <= 105)) {
        return;
    }
    
    // PERMITIR LETRAS (cualquier letra Unicode, incluyendo tildes)
    // Usamos una expresión regular para verificar si es una letra
    if (tecla.length === 1 && /[\p{L}\s]/u.test(tecla)) {
        return; // \p{L} = cualquier letra Unicode, \s = espacio
    }
    
    // Si no pasa ninguna validación, cancelar
    event.preventDefault();
}

function calcularLetraDni(num_dni) {
    const letras = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E'];
    return letras[num_dni % 23];
}

function asignarLetraDni() {
    let num_dni = document.getElementById("crear-numero-dni").value;
    if ( num_dni.length == 8 ) {
        document.getElementById("crear-letra-dni").value = calcularLetraDni(num_dni);
    } else {
        document.getElementById("crear-letra-dni").value = "";
    }
}
</script>

<div id="contenedor-clientes">
<!-- CLIENTES: En este apartado se obtiene la información de los clientes que han llevado a sus mascotas a la clínica según los criterios de búsqueda/creación/actualización/eliminación del usuario -->
<h2>Formulario Clientes</h2>

<!-- Formulario para buscar clientes. -->
<form action="<?php echo $base_url; ?>/public/index.php" method="post">
    <input type="hidden" name="action" value="buscarConFiltros">

    <fieldset>
        <legend id="click-buscar-cliente">Buscar clientes</legend>
        <div id="buscar-cliente">
        <fieldset id="fieldset-buscar-datos">
            <legend>Filtros de búsqueda</legend>
            <fieldset class="fieldset-buscar-datos-cliente">
                <legend>Datos personales</legend>
                <label>DNI:
                    <input type="text" name="buscar_dni" maxlength="9" size="10" placeholder="00000000Z">
                </label>
                <label>Nombre y Apellidos:
                    <input type="text" name="buscar_nombre" maxlength="50" size="30" placeholder="Nombre Apellido1 Apellido2">
                </label>
            </fieldset>
            <fieldset class="fieldset-buscar-datos-contacto">
                <legend>Datos de contacto</legend>
                <label>Dirección Postal:
                    <input type="text" name="buscar_direccion_postal" maxlength="150" size="50" placeholder="Calle Falsa 123 (Madrid)">
                </label>
                <label>Número de cuenta (IBAN):
                    <input type="text" name="buscar_num_cuenta" maxlength="24" size="30" placeholder="ES00 0000 0000 0000 0000 0000">
                </label>
            </fieldset>
            <p>Los botones de búsqueda le redirigirán a una nueva página con la información de su consulta.</p>
            <input type="submit" name="buscar_cliente" value="Busqueda con coincidencias">
            <!--<input type="submit" name="buscar_cliente_exacto" value="Busqueda exacta">-->
            <input type="submit" name="buscar_cliente_sin_filtro" value="Buscar todos" onclick="this.form.action.value='buscarSinFiltros'">
        </fieldset>
        </div>
    </fieldset>
</form>

<!-- Formulario para crear una nueva ficha de cliente. -->
<form action="<?php echo $base_url; ?>/public/index.php" method="post">
    <input type="hidden" name="action" value="crear">

    <fieldset>
        <legend id="click-crear-cliente">Crear cliente</legend>
        <div id="crear-cliente">
        <fieldset id="fieldset-crear">
            <legend>Creación de nuevo registro</legend>
            <fieldset class="fieldset-crear-datos-cliente">
                <legend>Datos personales</legend>
                <label>DNI:
                    <input type="text" name="crear_dni" id="crear-numero-dni" minlength="8" maxlength="8" size="10" placeholder="12345678" onkeydown="soloNumeros(event)" oninput="asignarLetraDni()" required>
                    <input type="text" name="letra_dni" id="crear-letra-dni" size="1" value="" placeholder="Z" readonly>
                </label>
                <label>Nombre y Apellidos:
                    <input type="text" name="crear_nombre" minlength="3" maxlength="50" size="30" placeholder="Nombre Apellido1 Apellido2" required>
                </label>
            </fieldset>
            <fieldset class="fieldset-crear-datos-contacto">
                <legend>Dirección postal</legend>
                <label>Tipo de vía:
                    <select name="crear_tipo_via">
                        <?php foreach ($tipos_via as $via): ?>
                            <option value="<?php echo htmlspecialchars($via); ?>">
                                <?php echo htmlspecialchars($via); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Dirección:
                    <input type="text" name="crear_direccion_postal" maxlength="120" size="35" placeholder="Nombre de la calle 123" onkeydown="soloTextoYNumeros(event)" required>
                </label>
                <label>Municipio:
                    <input type="text" name="crear_municipio" onkeydown="soloTextoYNumeros(event)">
                </label>
            </fieldset>
            <fieldset>
                <legend>Datos de pago</legend>
                <label>Número de cuenta (IBAN):
                    <input type="text" name="prefijo_iban" size="2" value="ES" readonly>
                    <input type="text" name="crear_num_cuenta" minlength="22" maxlength="22" size="25" onkeydown="soloNumeros(event)" placeholder="00 0000 0000 0000 0000 0000" required>
                </label>
            </fieldset>
            <p>* Tenga en cuenta que debe rellenar todos los campos para poder crear un nuevo registro de cliente.</p>
            <input type="submit" name="crear_cliente" value="Crear">
        </fieldset>
        </div>
    </fieldset>
</form>

<!-- Formulario para actualizar clientes. -->
<form action="<?php echo $base_url; ?>/public/index.php" method="post">
    <fieldset>
        <legend id="click-actualizar-cliente">Actualizar clientes</legend>
        <div id="actualizar-cliente">
            <fieldset>
                <legend>Actualizar un registro</legend>
                <fieldset>
                    <legend>Ordenar registros por:</legend>
                    <label>Dni
                        <input type="radio" name="busqueda_actualizar" value="dni" id="check-actualizar-dni">
                    </label>
                    <label>Nombre y Apellidos
                        <input type="radio" name="busqueda_actualizar" value="nombre" id="check-actualizar-nombre">
                    </label>
                    <label>Dirección Postal
                        <input type="radio" name="busqueda_actualizar" value="direccion" id="check-actualizar-direccion">
                    </label>
                    <label>Número de cuenta
                        <input type="radio" name="busqueda_actualizar" value="cuenta" id="check-actualizar-cuenta">
                    </label>
                </fieldset>
                <input type="submit" id="boton-actualizar-datos" name="actualizar_cliente_busqueda" value="Buscar registros">
            </fieldset>
        </div>
    </fieldset>
</form>

<!-- Formulario para eliminar clientes. -->
<form action="<?php echo $base_url; ?>/public/index.php" method="post">
    <fieldset>
        <legend id="click-eliminar-cliente">Eliminar clientes</legend>
        <div id="eliminar-cliente">
        </div>
    </fieldset>
</form>

</div>

<!-- INCLUIR FOOTER -->
<?php require_once __DIR__ . '/../layout/footer.php'; ?>