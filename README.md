# Recetario de Hyrule 🍲⚔️

> *Una guía (casi) completa de cocina para los aventureros de Hyrule*

[![Estado del proyecto](https://img.shields.io/badge/estado-en%20desarrollo-yellow)]()
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4)]()

## 📖 Índice

- [Acerca del proyecto](#acerca-del-proyecto)
- [Inspiración](#inspiración)
- [Características principales](#características-principales)
- [Tecnologías utilizadas](#tecnologías-utilizadas)
- [Estructura de la base de datos](#estructura-de-la-base-de-datos)
- [Instalación](#instalación)
- [Uso](#uso)
- [Próximas mejoras](#próximas-mejoras)
- [Créditos](#créditos)
- [Licencia](#licencia)

---

## 🎯 Acerca del proyecto

**Recetario de Hyrule** es una aplicación web diseñada para ayudar a los jugadores de *The Legend of Zelda: Breath of the Wild* a descubrir y gestionar las recetas disponibles en el juego. Desarrollada como proyecto final para el curso de **Implantación de Aplicaciones Web en Entornos Internet, Intranet y Extranet**, esta herramienta permite:

- Consultar más de **100 recetas** del juego
- Filtrar platos por **efectos** y **ingredientes**
- Localizar **dónde encontrar cada ingrediente** en el mapa de Hyrule
- Gestionar un **inventario personal** de ingredientes
- Descubrir qué recetas puedes cocinar con lo que tienes

### ¿Por qué este proyecto?

En *Breath of the Wild*, la cocina es una mecánica esencial, pero la información sobre recetas está dispersa por el juego y las wikis existentes. Este proyecto nace de la necesidad de:

- **Centralizar** toda la información culinaria de Hyrule
- **Agilizar** la búsqueda de recetas específicas
- **Personalizar** la experiencia según el progreso de cada jugador

---

## 🎮 Inspiración

El proyecto está inspirado en la rica gastronomía de Hyrule, donde cada ingrediente tiene propiedades únicas y cada combinación puede salvar la vida de un aventurero.

---

## ✨ Características principales

### Para todos los usuarios
- 🔍 **Búsqueda avanzada** de recetas por nombre, efecto o ingrediente
- 📋 **Listados completos** de todas las recetas e ingredientes
- 🗺️ **Localización de ingredientes** en las distintas regiones de Hyrule
- 🖼️ **Galería visual** con imágenes de cada plato e ingrediente

### Para usuarios registrados
- 📦 **Inventario personal**: guarda los ingredientes que has recolectado
- 🧪 **Simulador de cocina**: prueba qué recetas puedes hacer sin consumir ingredientes
- ✅ **Seguimiento de recetas**: marca las que ya has cocinado
- 🔄 **Sincronización** entre sesiones

### Por qué es diferente
- **Interactividad**: a diferencia de las wikis estáticas, permite filtrar y personalizar
- **Orientación al jugador**: diseñado pensando en la experiencia real de juego
- **Estructura clara**: organización por efectos, regiones y combinaciones

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Uso |
|------------|-----|
| **HTML5** | Estructura semántica de las páginas |
| **CSS3** | Estilos, diseño responsivo y animaciones |
| **JavaScript / jQuery** | Interactividad y peticiones asíncronas |
| **PHP 8.x** | Lógica del servidor y gestión de sesiones |
| **MySQL** | Almacenamiento de recetas, ingredientes y usuarios |
| **XAMPP** | Entorno de desarrollo local |
| **InfinityFree** | Proveedor de alojamiento web (hosting) gratuito |

---

## 📊 Estructura de la base de datos

La base de datos está diseñada para reflejar las relaciones del juego:

```
📁 recetario_hyrule
├── 📄 recetas              (47 recetas base + 54 variantes)
├── 📄 ingredientes         (82 ingredientes con efectos y localizaciones)
├── 📄 efectos              (12 tipos: Gélido, Recio, Vivaz, etc.)
├── 📄 localizaciones       (20 regiones de Hyrule)
├── 📄 usuarios             (registro y autenticación)
├── 📄 inventarios          (relación usuario - ingrediente)
├── 📄 recetas_ingredientes (qué ingredientes necesita cada receta)
├── 📄 recetas_efectos      (qué efecto produce cada receta)
└── 📄 ingredientes_localizaciones (dónde encontrar cada ingrediente)
```

### Datos incluidos

- ✅ **47 recetas base** (desde "Arroz con cangrejo" hasta "Verduras a la parrilla")
- ✅ **54 variantes con efecto** (brochetas y setas en todas sus versiones)
- ✅ **82 ingredientes** con descripciones e imágenes
- ✅ **20 localizaciones** en las 7 regiones de Hyrule
- ✅ **12 tipos de efectos** con sus descripciones

---

## 🚀 Instalación

### Requisitos previos

- [XAMPP](https://www.apachefriends.org/) (o cualquier servidor con PHP 8+ y MySQL)
- Navegador web moderno

### Pasos de instalación

1. **Clona el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/recetario-hyrule.git
   ```

2. **Copia los archivos al servidor**
   ```bash
   # Si usas XAMPP:
   cp -r recetario-hyrule /Applications/XAMPP/htdocs/
   ```

3. **Importa la base de datos**
   - Abre phpMyAdmin (http://localhost/phpmyadmin)
   - Crea una nueva base de datos llamada `recetario_hyrule`
   - Importa el archivo `database/recetario_hyrule.sql`

4. **Configura la conexión**
   ```php
   # config/database.php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'recetario_hyrule');
   ```

5. **Accede a la aplicación**
   ```
   http://localhost/recetario-hyrule
   ```

---

## 💻 Uso

### Buscar recetas
1. En la página principal, usa el buscador por nombre
2. Filtra por efecto usando el menú lateral
3. Selecciona ingredientes para ver qué recetas puedes hacer

### Explorar ingredientes
1. Ve a la sección "Ingredientes"
2. Busca por nombre o filtra por región
3. Haz clic en cualquier ingrediente para ver dónde encontrarlo

### Gestionar tu inventario (requiere registro)
1. Regístrate con un correo y contraseña
2. Desde tu perfil, añade los ingredientes que tienes
3. Usa "Mis recetas posibles" para ver qué puedes cocinar

---

## 🔮 Próximas mejoras

- [ ] Implementar el sistema de **variantes de recetas por efecto** (ya diseñado, pendiente de integración)
- [ ] Añadir **mapa interactivo** con localizaciones de ingredientes
- [ ] Permitir **valoraciones y comentarios** de usuarios
- [ ] Crear **sistema de retos semanales** ("Cocina 3 recetas con efecto Recio")
- [ ] Soporte para **múltiples idiomas** (español/inglés)
- [ ] **API pública** para que otras aplicaciones accedan a los datos

---

## 📚 Créditos

### Datos y contenido
- **Nintendo** — Por crear el universo de Hyrule y *Breath of the Wild*
- **Zelda Wiki (Fandom)** — Por la documentación detallada de recetas e ingredientes
- **Guías de Vandal e IGN** — Por la información estructurada sobre el juego

### Imágenes
- Capturas de pantalla y arte oficial: **Nintendo**
- Edición y optimización: Helena Martínez Pijuan

### Desarrollo
- **Helena Martínez Pijuan** — Diseño, desarrollo y documentación
- **Academia Colón** — Asesoramiento y seguimiento del proyecto

---

## 📄 Licencia

Este proyecto es de código abierto bajo la licencia **CC BY-NC-SA**.

**Nota sobre propiedad intelectual**: Todo el contenido relacionado con *The Legend of Zelda* (nombres, imágenes, conceptos) pertenece a **Nintendo**. Este proyecto es un **trabajo educativo** sin ánimo de lucro, creado con fines de aprendizaje y como homenaje a la comunidad de jugadores.

<br><a href="recetario-hyrule.infinityfreeapp.com">Recetario Hyrule</a> © 2026 by <a href="github.com/helenamartinezpijuan">Helena Martinez Pijuan</a> is licensed under <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA 4.0</a><img src="https://mirrors.creativecommons.org/presskit/icons/cc.svg" alt="" style="max-width: 1em;max-height:1em;margin-left: .2em;"><img src="https://mirrors.creativecommons.org/presskit/icons/by.svg" alt="" style="max-width: 1em;max-height:1em;margin-left: .2em;"><img src="https://mirrors.creativecommons.org/presskit/icons/nc.svg" alt="" style="max-width: 1em;max-height:1em;margin-left: .2em;"><img src="https://mirrors.creativecommons.org/presskit/icons/sa.svg" alt="" style="max-width: 1em;max-height:1em;margin-left: .2em;">

---

## 📬 Contacto

¿Preguntas, sugerencias o quieres contribuir?

- **Email**: helenamartinezpijuan@gmail.com
- **GitHub**: [@helenamartinezpijuan](https://github.com/helenamartinezpijuan)

---

*"It's dangerous to go alone! Take this — and a good meal."* 🗡️🍎
