<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_block_textblock_to_all_courses_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2024010100) {
        // Aquí se añadirán actualizaciones futuras cuando sea necesario
        upgrade_plugin_savepoint(true, 2024010100, 'block', 'textblock_to_all_courses');
    }

    return true;
}
