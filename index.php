<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('edit_form.php');
require_once($CFG->dirroot . '/blocks/textblock_to_all_courses/classes/util.php');

// Configurar la página
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/textblock_to_all_courses/index.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('manage_blocks', 'block_textblock_to_all_courses'));
$PAGE->set_heading(get_string('manage_blocks', 'block_textblock_to_all_courses'));

// Verificar capacidades
require_login();
require_capability('block/textblock_to_all_courses:manage', context_system::instance());

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);

// Procesar acciones
if ($action === 'delete' && $id && confirm_sesskey()) {
    // Obtener el bloque antes de eliminarlo
    $block = $DB->get_record('block_textblock_to_all_courses', array('id' => $id));
    if ($block) {
        // Primero eliminar las instancias del bloque
        \block_textblock_to_all_courses\util::remove_block_instances($block);
        // Luego eliminar el registro del bloque
        $DB->delete_records('block_textblock_to_all_courses', array('id' => $id));
        
        // Trigger del evento
        $event = \block_textblock_to_all_courses\event\block_deleted::create(array(
            'context' => context_system::instance(),
            'objectid' => $id,
            'other' => array('title' => $block->title)
        ));
        $event->trigger();
    }
    redirect($PAGE->url, get_string('success_delete', 'block_textblock_to_all_courses'));
}

// Instanciar formulario
$mform = new block_textblock_to_all_courses_edit_form();

if ($mform->is_cancelled()) {
    redirect($PAGE->url);
} else if ($data = $mform->get_data()) {
    $record = new stdClass();
    $record->title = $data->title;
    $record->content = $data->content_editor['text']; // Corregido para usar content_editor
    $record->courses = json_encode($data->courses);
    $record->position = $data->position;
    $record->weight = $data->weight;
    // Asegurar que roles es un array antes de codificarlo
    $roles = empty($data->roles) ? array(0) : (array)$data->roles;
    $record->roles = json_encode($roles);
    $record->icon = !empty($data->icon) ? clean_param($data->icon, PARAM_TEXT) : '';  // Guardar cadena vacía en lugar de null
    $record->timemodified = time();
    $record->usermodified = $USER->id;

    if (empty($data->id)) {
        $record->timecreated = time();
        $id = $DB->insert_record('block_textblock_to_all_courses', $record);
        $record->id = $id;
        \block_textblock_to_all_courses\util::deploy_block($record);

        // Trigger del evento created
        $event = \block_textblock_to_all_courses\event\block_created::create(array(
            'context' => context_system::instance(),
            'objectid' => $id,
            'other' => array('title' => $record->title)
        ));
        $event->trigger();

        redirect($PAGE->url, get_string('success_add', 'block_textblock_to_all_courses'));
    } else {
        $record->id = $data->id; // Asegurarnos de que el ID está establecido
        $DB->update_record('block_textblock_to_all_courses', $record);
        \block_textblock_to_all_courses\util::deploy_block($record);

        // Trigger del evento updated
        $event = \block_textblock_to_all_courses\event\block_updated::create(array(
            'context' => context_system::instance(),
            'objectid' => $record->id,
            'other' => array('title' => $record->title)
        ));
        $event->trigger();

        redirect($PAGE->url, get_string('success_edit', 'block_textblock_to_all_courses'));
    }
}

// Obtener bloques existentes
$blocks = $DB->get_records('block_textblock_to_all_courses', null, 'timecreated DESC');

echo $OUTPUT->header();

// Botón para agregar nuevo bloque
echo html_writer::div(
    $OUTPUT->single_button(
        new moodle_url($PAGE->url, array('action' => 'add')),
        get_string('addnewblock', 'block_textblock_to_all_courses')
    ),
    'mb-3'
);

// Tabla de bloques existentes
$table = new html_table();
$table->head = array(
    get_string('blocktitle', 'block_textblock_to_all_courses'),
    get_string('selectposition', 'block_textblock_to_all_courses'),
    get_string('weight', 'block_textblock_to_all_courses'),
    get_string('timemodified', 'block_textblock_to_all_courses'), // Cambiado de 'core' a nuestro plugin
    get_string('actions', 'block_textblock_to_all_courses')       // Cambiado de 'core' a nuestro plugin
);
$table->attributes['class'] = 'generaltable';

foreach ($blocks as $block) {
    $buttons = array();
    
    // Botón editar
    $buttons[] = html_writer::link(
        new moodle_url($PAGE->url, array('action' => 'edit', 'id' => $block->id)),
        $OUTPUT->pix_icon('t/edit', get_string('edit')),
        array('title' => get_string('edit'))
    );
    
    // Botón eliminar
    $buttons[] = html_writer::link(
        new moodle_url($PAGE->url, array('action' => 'delete', 'id' => $block->id, 'sesskey' => sesskey())),
        $OUTPUT->pix_icon('t/delete', get_string('delete')),
        array('title' => get_string('delete'))
    );
    
    // Añadir icono junto al título
    $title = '';
    if (!empty($block->icon)) {
        $title .= $OUTPUT->pix_icon($block->icon, '');
    }
    $title .= ' ' . format_string($block->title);
    
    $table->data[] = array(
        $title, // Cambiado de format_string($block->title)
        $block->position == 'side-pre' ? get_string('left', 'block_textblock_to_all_courses') : get_string('right', 'block_textblock_to_all_courses'),
        $block->weight,
        userdate($block->timemodified, '%d/%m/%Y %H:%M'), // Cambiado el formato de fecha
        implode(' ', $buttons)
    );
}

echo html_writer::table($table);

// Mostrar formulario si es necesario
if ($action === 'add' || $action === 'edit') {
    if ($action === 'edit' && $id) {
        $block = $DB->get_record('block_textblock_to_all_courses', array('id' => $id));
        if ($block) {
            $block->courses = json_decode($block->courses);
            // Decodificar roles solo si es un string JSON
            $block->roles = !empty($block->roles) && is_string($block->roles) ? 
                           json_decode($block->roles) : 
                           (empty($block->roles) ? array(0) : (array)$block->roles);
            $mform->set_data($block);
        }
    }
    $mform->display();
}

echo $OUTPUT->footer();
