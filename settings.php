<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = null;

    $ADMIN->add('blocksettings', new admin_setting_heading(
        'block_textblock_to_all_courses_manage',  // Identificador único
        get_string('pluginmanage', 'block_textblock_to_all_courses'), // Texto del enlace
        '<a href="' . new moodle_url('/blocks/textblock_to_all_courses/manage.php') . '">' . 
        get_string('pluginmanage', 'block_textblock_to_all_courses') . '</a>' // URL y enlace
    ));
}
