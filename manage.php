<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Configuración de la página de administración
admin_externalpage_setup('block_textblock_to_all_courses_manage');

require_login();
require_capability('block/textblock_to_all_courses:manage', context_system::instance());

// Configuración básica de la página sin usar admin_externalpage_setup
$PAGE->set_url('/blocks/textblock_to_all_courses/manage.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('manage_blocks', 'block_textblock_to_all_courses'));
$PAGE->set_heading(get_string('manage_blocks', 'block_textblock_to_all_courses'));

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);

if ($action === 'delete' && $id) {
    require_sesskey();
    $DB->delete_records('block_textblock_to_all', ['id' => $id]);
    redirect($PAGE->url);
}

echo $OUTPUT->header();

$blocks = $DB->get_records('block_textblock_to_all');

$table = new html_table();
$table->head = [
    get_string('title', 'block_textblock_to_all_courses'),
    get_string('enabled', 'block_textblock_to_all_courses'),
    get_string('position', 'block_textblock_to_all_courses'),
    get_string('weight', 'block_textblock_to_all_courses'),
    get_string('edit'),
    get_string('delete')
];

foreach ($blocks as $block) {
    $editurl = new moodle_url('/blocks/textblock_to_all_courses/edit.php', ['id' => $block->id]);
    $deleteurl = new moodle_url('/blocks/textblock_to_all_courses/manage.php', 
        ['action' => 'delete', 'id' => $block->id, 'sesskey' => sesskey()]);

    $table->data[] = [
        $block->title,
        $block->enabled ? get_string('yes') : get_string('no'),
        $block->position,
        $block->weight,
        html_writer::link($editurl, get_string('edit')),
        html_writer::link($deleteurl, get_string('delete'), 
            ['onclick' => 'return confirm("' . get_string('areyousure') . '");'])
    ];
}

echo html_writer::table($table);

$addurl = new moodle_url('/blocks/textblock_to_all_courses/edit.php');
echo html_writer::link($addurl, get_string('add'), ['class' => 'btn btn-primary']);

echo $OUTPUT->footer();
