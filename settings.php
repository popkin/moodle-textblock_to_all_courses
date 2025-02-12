<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading(
        'block_textblock_to_all_courses_settings',
        '',
        get_string('manage_blocks', 'block_textblock_to_all_courses')
    ));

    $url = new moodle_url('/blocks/textblock_to_all_courses/manage.php');
    $settings->add(new admin_setting_confightml(
        'block_textblock_to_all_courses_manage',
        '',
        '<a href="' . $url . '" class="btn btn-primary">' . 
        get_string('manage_blocks', 'block_textblock_to_all_courses') . '</a>',
        ''
    ));
}
