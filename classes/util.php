<?php
/**
 * Utility functions for Text Block to All Courses
 *
 * @package    block_textblock_to_all_courses
 * @copyright  2024 Toni Lodev <tonilopezdev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_textblock_to_all_courses;

defined('MOODLE_INTERNAL') || die();

/**
 * Utility class for managing block instances across courses
 */
class util {
    /**
     * Ensures a block instance exists in the specified course
     *
     * Creates a new block instance if it doesn't exist, or updates
     * an existing one with new position and weight settings.
     *
     * @param int $courseid The ID of the course
     * @param stdClass $textblock The block configuration object
     */
    public static function ensure_block_instance($courseid, $textblock) {
        global $DB;

        // Verificar si el bloque ya existe en el curso
        $context = \context_course::instance($courseid);
        $existing = $DB->get_record('block_instances', array(
            'blockname' => 'textblock_to_all_courses',
            'parentcontextid' => $context->id
        ));

        if (!$existing) {
            // Crear nueva instancia del bloque
            $blockinstance = new \stdClass();
            $blockinstance->blockname = 'textblock_to_all_courses';
            $blockinstance->parentcontextid = $context->id;
            $blockinstance->showinsubcontexts = 0;
            $blockinstance->pagetypepattern = 'course-view-*';
            $blockinstance->defaultregion = $textblock->position;
            $blockinstance->defaultweight = $textblock->weight;
            $blockinstance->configdata = '';
            $blockinstance->timecreated = time();
            $blockinstance->timemodified = time();

            $DB->insert_record('block_instances', $blockinstance);
        } else {
            // Actualizar posición y peso si es necesario
            $existing->defaultregion = $textblock->position;
            $existing->defaultweight = $textblock->weight;
            $existing->timemodified = time();
            $DB->update_record('block_instances', $existing);
        }
    }

    /**
     * Deploys a block to all specified courses
     *
     * @param stdClass $textblock The block configuration object
     */
    public static function deploy_block($textblock) {
        $courses = json_decode($textblock->courses);
        
        // Verificar si es string 'all' y convertirlo a array
        if (is_string($courses) && $courses === 'all') {
            $courses = ['all'];
        }
        
        if (in_array('all', $courses)) {
            // Desplegar en todos los cursos
            $allcourses = get_courses();
            foreach ($allcourses as $course) {
                if ($course->id == SITEID) continue;
                self::ensure_block_instance($course->id, $textblock);
            }
        } else {
            // Desplegar solo en los cursos seleccionados
            foreach ($courses as $courseid) {
                self::ensure_block_instance($courseid, $textblock);
            }
        }
    }

    /**
     * Removes block instances from specified courses
     *
     * @param stdClass $textblock The block configuration object
     */
    public static function remove_block_instances($textblock) {
        global $DB;
        
        // Obtener los cursos donde está el bloque
        $courses = json_decode($textblock->courses);
        
        if (in_array('all', $courses)) {
            // Eliminar de todos los cursos
            $allcourses = get_courses();
            foreach ($allcourses as $course) {
                if ($course->id == SITEID) continue;
                self::remove_from_course($course->id);
            }
        } else {
            // Eliminar solo de los cursos seleccionados
            foreach ($courses as $courseid) {
                self::remove_from_course($courseid);
            }
        }
    }

    /**
     * Removes a block instance from a specific course
     *
     * @param int $courseid The ID of the course
     */
    private static function remove_from_course($courseid) {
        global $DB;
        
        $context = \context_course::instance($courseid);
        $DB->delete_records('block_instances', array(
            'blockname' => 'textblock_to_all_courses',
            'parentcontextid' => $context->id
        ));
    }
}
