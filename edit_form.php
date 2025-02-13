<?php
/**
 * Form for editing Text Block to All Courses blocks
 *
 * @package    block_textblock_to_all_courses
 * @copyright  2024 Toni Lodev <tonilopezdev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Form class for editing text blocks
 *
 * Provides interface for creating and editing text blocks,
 * including course selection, role restrictions, and icon selection.
 */
class block_textblock_to_all_courses_edit_form extends moodleform {
    /**
     * Form definition
     *
     * Defines all form elements and their properties
     */
    protected function definition() {
        global $DB, $OUTPUT;

        $mform = $this->_form;
        $block = isset($this->_customdata['block']) ? $this->_customdata['block'] : null;

        // Título
        $mform->addElement('text', 'title', get_string('blocktitle', 'block_textblock_to_all_courses'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('error_title_required', 'block_textblock_to_all_courses'), 'required', null, 'client');

        // Contenido
        $editoroptions = array(
            'subdirs' => 0,
            'maxfiles' => 0,
            'context' => context_system::instance(),
            'noclean' => true
        );
        $mform->addElement('editor', 'content_editor', get_string('blockcontent', 'block_textblock_to_all_courses'), null, $editoroptions);
        $mform->setType('content_editor', PARAM_RAW);
        $mform->addRule('content_editor', get_string('error_content_required', 'block_textblock_to_all_courses'), 'required', null, 'client');

        // Selector de cursos
        $courseoptions = array(
            'all' => get_string('allcourses', 'block_textblock_to_all_courses')
        );
        $courses = get_courses();
        foreach ($courses as $course) {
            if ($course->id == SITEID) continue;
            $courseoptions[$course->id] = format_string($course->fullname);
        }
        $mform->addElement('autocomplete', 'courses', get_string('selectcourses', 'block_textblock_to_all_courses'), 
                          $courseoptions, array('multiple' => true));
        $mform->addRule('courses', get_string('error_courses_required', 'block_textblock_to_all_courses'), 'required', null, 'client');

        // Posición
        $positions = array(
            'side-pre' => get_string('left', 'block_textblock_to_all_courses'),
            'side-post' => get_string('right', 'block_textblock_to_all_courses')
        );
        $mform->addElement('select', 'position', get_string('selectposition', 'block_textblock_to_all_courses'), $positions);
        $mform->setDefault('position', 'side-pre');

        // Peso
        $mform->addElement('text', 'weight', get_string('weight', 'block_textblock_to_all_courses'));
        $mform->setType('weight', PARAM_INT);
        $mform->setDefault('weight', 0);

        // Roles
        $syscontext = context_system::instance();
        $roles = array();
        
        // Obtener roles de profesor y estudiante
        $teacherarchetype = $DB->get_records('role', array('archetype' => 'teacher'));
        $editingteacherarchetype = $DB->get_records('role', array('archetype' => 'editingteacher'));
        
        foreach ($teacherarchetype as $role) {
            $roles[$role->id] = role_get_name($role, $syscontext);
        }
        foreach ($editingteacherarchetype as $role) {
            $roles[$role->id] = role_get_name($role, $syscontext);
        }
        
        // Añadir opción "Todos los usuarios"
        $roles = array(0 => get_string('everyone', 'block_textblock_to_all_courses')) + $roles;

        // Modificar el selector de roles para permitir múltiples
        $mform->addElement('autocomplete', 'roles', get_string('selectroles', 'block_textblock_to_all_courses'), 
                          $roles, array('multiple' => true));
        $mform->setDefault('roles', array(0)); // Por defecto, visible para todos
        $mform->addHelpButton('roles', 'selectroles', 'block_textblock_to_all_courses');

        // Selector de icono - Versión con select y opción vacía
        $icons = array(
            '' => get_string('no_icon', 'block_textblock_to_all_courses'),
            'i/asterisk' => $OUTPUT->pix_icon('i/asterisk', '') . ' ' . get_string('icon_asterisk', 'block_textblock_to_all_courses'),
            'i/info' => $OUTPUT->pix_icon('i/info', '') . ' ' . get_string('icon_info', 'block_textblock_to_all_courses'),
            'i/warning' => $OUTPUT->pix_icon('i/warning', '') . ' ' . get_string('icon_warning', 'block_textblock_to_all_courses'),
            'i/bullhorn' => $OUTPUT->pix_icon('i/bullhorn', '') . ' ' . get_string('icon_bullhorn', 'block_textblock_to_all_courses'),
            't/message' => $OUTPUT->pix_icon('t/message', '') . ' ' . get_string('icon_message', 'block_textblock_to_all_courses'),
            'i/calendar' => $OUTPUT->pix_icon('i/calendar', '') . ' ' . get_string('icon_calendar', 'block_textblock_to_all_courses'),
            'i/star' => $OUTPUT->pix_icon('i/star', '') . ' ' . get_string('icon_star', 'block_textblock_to_all_courses')
        );

        $mform->addElement('select', 'icon', get_string('select_icon', 'block_textblock_to_all_courses'), $icons);
        $mform->setDefault('icon', '');  // Por defecto sin icono
        $mform->setType('icon', PARAM_TEXT);

        // ID oculto para edición
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', 0);

        if ($block) {
            $mform->setDefault('id', $block->id);
            $this->set_data($block);
        }

        $this->add_action_buttons();
    }

    /**
     * Form validation
     *
     * @param array $data Form data
     * @param array $files Uploaded files
     * @return array Array of errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        if (empty($data['courses'])) {
            $errors['courses'] = get_string('error_courses_required', 'block_textblock_to_all_courses');
        }
        
        return $errors;
    }

    /**
     * Sets the form data from a block object
     *
     * @param stdClass $block Block data object
     */
    public function set_data($block) {
        if (!empty($block->content)) {
            $block->content_editor = array(
                'text' => $block->content,
                'format' => FORMAT_HTML
            );
        }

        // Convertir los roles de JSON a array si viene como string
        if (!empty($block->roles) && is_string($block->roles)) {
            $block->roles = json_decode($block->roles);
        } else if (empty($block->roles)) {
            $block->roles = array(0);
        }
        
        parent::set_data($block);
    }

    public function get_data() {
        $data = parent::get_data();
        if ($data) {
            $data->content = $data->content_editor['text'];
            // No codificar los roles aquí, se hará en index.php
            if (empty($data->roles)) {
                $data->roles = array(0);
            }
        }
        return $data;
    }
}
