# Text Block to All Courses

[![Moodle Plugin CI](https://github.com/popkin/moodle-textblock_to_all_courses/workflows/Moodle%20Plugin%20CI/badge.svg)](https://github.com/popkin/moodle-textblock_to_all_courses/actions)
[![GitHub License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

Un plugin de Moodle que permite desplegar bloques de texto en múltiples cursos simultáneamente.

## 🎯 Características

- Crear bloques de texto y desplegarlos en múltiples cursos
- Seleccionar la posición del bloque (izquierda o derecha)
- Personalizar con iconos predefinidos
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
   - Contenido (soporta HTML)
   - Cursos objetivo (específicos o todos)
   - Roles con acceso
   - Posición (izquierda/derecha)
   - Orden de visualización
   - Icono (opcional)

## 🔒 Permisos

El plugin utiliza las siguientes capacidades:
- `block/textblock_to_all_courses:addinstance`: Permite añadir el bloque
- `block/textblock_to_all_courses:myaddinstance`: Permite añadir el bloque al Área personal
- `block/textblock_to_all_courses:manage`: Permite gestionar los bloques

## 🌐 Idiomas soportados

- Español (es)
- Inglés (en)

## 📦 Versión

- Versión: 1.0.8
- Build: 20240125
- Madurez: MATURITY_STABLE
- Requiere Moodle: 4.3.2+

## 👨‍💻 Autor

Desarrollado por Toni Lodev

- GitHub: [@popkin](https://github.com/popkin)
- Email: tonilopezdev@gmail.com

## 📄 Licencia

Este proyecto está licenciado bajo GNU GPL v3 - ver el archivo [LICENSE](LICENSE) para más detalles.

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Haz un Fork del proyecto
2. Crea una rama para tu funcionalidad (`git checkout -b feature/AmazingFeature`)
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## ❗ Problemas conocidos

Si encuentras algún problema, por favor repórtalo en la sección de [issues](https://github.com/popkin/moodle-textblock_to_all_courses/issues).