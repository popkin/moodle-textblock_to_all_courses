<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_block_textblock_to_all_courses_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2024012504) {
        // Define field icon
        $table = new xmldb_table('block_textblock_to_all_courses');
        if (!$dbman->table_exists($table)) {
            throw new moodle_exception('tabla_no_existe', 'block_textblock_to_all_courses');
        }

        $field = new xmldb_field('icon',
            XMLDB_TYPE_CHAR,
            '50',
            null,
            XMLDB_NOTNULL,
            null,
            'i/asterisk',
            'usermodified');

        // Añadir el campo solo si no existe
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2024012504, 'block', 'textblock_to_all_courses');
    }

    if ($oldversion < 2024012506) {
        // Definir el campo icon con las nuevas especificaciones
        $table = new xmldb_table('block_textblock_to_all_courses');
        $field = new xmldb_field('icon', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, '', 'usermodified');

        // Primero cambiar el campo a nullable y sin valor por defecto
        $field->setNotnull(false);
        $field->setDefault(null);

        // Modificar el campo
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_notnull($table, $field);
            $dbman->change_field_default($table, $field);
        }

        // Actualizar registros existentes que tengan 'i/asterisk' a cadena vacía
        $DB->set_field('block_textblock_to_all_courses', 'icon', '', array('icon' => 'i/asterisk'));

        upgrade_plugin_savepoint(true, 2024012506, 'block', 'textblock_to_all_courses');
    }

    return true;
}
