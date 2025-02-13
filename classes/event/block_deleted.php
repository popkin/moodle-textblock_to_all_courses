<?php
/**
 * Block deleted event class
 *
 * @package    block_textblock_to_all_courses
 * @copyright  2024 Toni Lodev <tonilopezdev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_textblock_to_all_courses\event;

defined('MOODLE_INTERNAL') || die();

class block_deleted extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'd';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'block_textblock_to_all_courses';
    }

    public static function get_name() {
        return get_string('eventblockdeleted', 'block_textblock_to_all_courses');
    }

    public function get_description() {
        return "The user with id '{$this->userid}' deleted the text block with id '{$this->objectid}'.";
    }

    public function get_url() {
        return new \moodle_url('/blocks/textblock_to_all_courses/index.php');
    }
}
