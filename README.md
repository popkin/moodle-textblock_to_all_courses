# Text Block to All Courses

Un plugin de Moodle que permite desplegar bloques de texto en múltiples cursos simultáneamente.

## 🎯 Características

- Crear bloques de texto y desplegarlos en múltiples cursos
- Configurar la posición del bloque (izquierda o derecha)
- Restringir la visibilidad por roles
- Gestión centralizada de todos los bloques
- Soporte multiidioma (Español e Inglés)

## 📋 Requisitos

- Moodle 4.3.2+ (Build: 20240125)
- PHP 8.0 o superior
- Base de datos: MySQL 5.7+ / MariaDB 10.4+ / PostgreSQL 13+

## 🔧 Instalación

1. Descargar el plugin
2. Copiar los archivos a la carpeta `/blocks/textblock_to_all_courses/`
3. Acceder como administrador a tu sitio Moodle
4. Ir a Administración del sitio > Notificaciones
5. Seguir el proceso de instalación

## 🚀 Uso

1. Acceder a Administración del sitio > Bloques > Mass Deploy Text Blocks!
2. Crear un nuevo bloque usando el botón "Añadir nuevo bloque de texto"
3. Configurar:
   - Título del bloque
   - Contenido
   - Cursos objetivo
   - Roles con acceso
   - Posición
   - Orden de visualización

## 🔒 Permisos

El plugin utiliza las siguientes capacidades:
- `block/textblock_to_all_courses:addinstance`
- `block/textblock_to_all_courses:myaddinstance`
- `block/textblock_to_all_courses:manage`

## 🌐 Idiomas soportados

- Español (es)
- Inglés (en)

## 📝 Versión

- Versión: 1.0.2
- Build: 20240125
- Madurez: MATURITY_STABLE
- Compatibilidad: Moodle 4.3.2+

## 👨‍💻 Autor

[Tu nombre/organización]

## 📄 Licencia

[Especificar la licencia]