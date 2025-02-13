<?php
defined('MOODLE_INTERNAL') || die;

// Crear una única entrada en la configuración
if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'blocksettingtextblock_to_all_courses',
        get_string('pluginname', 'block_textblock_to_all_courses')
    );

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_heading(
            'block_textblock_to_all_courses_manage_link',
            '',
            html_writer::link(
                new moodle_url('/blocks/textblock_to_all_courses/index.php'),
                get_string('manage_blocks', 'block_textblock_to_all_courses'),
                ['class' => 'btn btn-primary']
            )
        ));
    }
}
