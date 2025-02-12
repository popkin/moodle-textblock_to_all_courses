<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/formslib.php');

class block_textblock_edit_form extends moodleform {
    protected function definition() {
        global $DB;

        $mform = $this->_form;

        $mform->addElement('text', 'title', get_string('title', 'block_textblock_to_all_courses'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');

        $mform->addElement('editor', 'content', get_string('content', 'block_textblock_to_all_courses'));
        $mform->setType('content', PARAM_RAW);
        $mform->addRule('content', null, 'required');

        $courses = $DB->get_records_menu('course', null, 'fullname', 'id,fullname');
        $mform->addElement('select', 'courses', get_string('courses', 'block_textblock_to_all_courses'), 
            $courses, ['multiple' => true]);

        $roles = role_get_names();
        $rolesarray = [];
        foreach ($roles as $role) {
            $rolesarray[$role->id] = $role->localname;
        }
        $mform->addElement('select', 'restricted_roles', 
            get_string('restricted_roles', 'block_textblock_to_all_courses'), 
            $rolesarray, ['multiple' => true]);

        $positions = ['side-pre' => get_string('left'), 'side-post' => get_string('right')];
        $mform->addElement('select', 'position', 
            get_string('position', 'block_textblock_to_all_courses'), $positions);

        $mform->addElement('text', 'weight', get_string('weight', 'block_textblock_to_all_courses'));
        $mform->setType('weight', PARAM_INT);
        $mform->setDefault('weight', 0);

        $mform->addElement('advcheckbox', 'enabled', get_string('enabled', 'block_textblock_to_all_courses'));
        $mform->setDefault('enabled', 1);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }
}

// Actualizado para usar el mismo identificador de página de administración
admin_externalpage_setup('block_textblock_to_all_courses_manage');
require_capability('block/textblock_to_all_courses:manage', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);
$PAGE->set_url('/blocks/textblock_to_all_courses/edit.php', ['id' => $id]);
$PAGE->set_title($id ? get_string('edit') : get_string('add'));
$PAGE->set_heading($id ? get_string('edit') : get_string('add'));

$form = new block_textblock_edit_form();

if ($id) {
    $data = $DB->get_record('block_textblock_to_all', ['id' => $id], '*', MUST_EXIST);
    $form->set_data($data);
}

if ($data = $form->get_data()) {
    $record = new stdClass();
    $record->title = $data->title;
    $record->content = $data->content['text'];
    $record->courses = !empty($data->courses) ? json_encode($data->courses) : null;
    $record->restricted_roles = !empty($data->restricted_roles) ? 
        json_encode($data->restricted_roles) : null;
    $record->position = $data->position;
    $record->weight = $data->weight;
    $record->enabled = $data->enabled;

    if ($id) {
        $record->id = $id;
        $DB->update_record('block_textblock_to_all', $record);
    } else {
        $DB->insert_record('block_textblock_to_all', $record);
    }

    redirect(new moodle_url('/blocks/textblock_to_all_courses/manage.php'));
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();
