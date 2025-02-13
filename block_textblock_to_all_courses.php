<?php
/**
 * Text Block to All Courses - Main block class
 *
 * @package    block_textblock_to_all_courses
 * @copyright  2024 Toni Lodev <tonilopezdev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block class for displaying text content across multiple courses
 *
 * This block allows administrators to create and manage text blocks
 * that can be displayed in multiple courses simultaneously.
 */
class block_textblock_to_all_courses extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_textblock_to_all_courses');
    }

    /**
     * Get the block content
     *
     * Retrieves and formats the block content, applying role restrictions
     * and adding icons if configured.
     *
     * @return stdClass Block content object
     */
    public function get_content() {
        global $DB, $COURSE, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        $sql = "SELECT * FROM {block_textblock_to_all_courses} 
                WHERE courses LIKE ? OR courses LIKE ?
                ORDER BY weight ASC
                LIMIT 1";
        $params = ['%"'.$COURSE->id.'"%', '%"all"%'];
        
        if ($block = $DB->get_record_sql($sql, $params)) {
            // Verificar roles si están definidos
            if (!empty($block->roles)) {
                $roles = json_decode($block->roles);
                if (!is_array($roles)) {
                    $roles = [$roles];
                }
                if (!empty($roles) && !$this->has_any_role($roles)) {
                    return $this->content;
                }
            }

            // Establecer el título con o sin icono
            if (!empty($block->icon) && $block->icon !== null) {
                $this->title = $OUTPUT->pix_icon($block->icon, '') . ' ' . format_string($block->title);
            } else {
                $this->title = format_string($block->title);
            }
            
            $this->content->text = $block->content;
        }

        return $this->content;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function has_config() {
        return true;
    }

    /**
     * Check if user has any of the required roles
     *
     * @param array $roles Array of role IDs to check
     * @return bool True if user has any of the roles or if roles is empty
     */
    private function has_any_role($roles) {
        global $USER, $COURSE;
        
        // Si roles es 0 o vacío, el bloque es visible para todos
        if (empty($roles) || $roles == 0) {
            return true;
        }
        
        $context = context_course::instance($COURSE->id);
        $userroles = get_user_roles($context, $USER->id);
        
        foreach ($userroles as $role) {
            if (in_array($role->roleid, $roles)) {
                return true;
            }
        }
        return false;
    }
}
