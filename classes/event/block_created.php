<?php
/**
 * Block created event class
 *
 * @package    block_textblock_to_all_courses
 * @copyright  2024 Toni Lodev <tonilopezdev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_textblock_to_all_courses\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event triggered when a new text block is created
 */
class block_created extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'block_textblock_to_all_courses';
    }

    public static function get_name() {
        return get_string('eventblockcreated', 'block_textblock_to_all_courses');
    }

    public function get_description() {
        return "The user with id '{$this->userid}' created a new text block with id '{$this->objectid}'."; 
    }

    public function get_url() {
        return new \moodle_url('/blocks/textblock_to_all_courses/index.php');
    }
}
