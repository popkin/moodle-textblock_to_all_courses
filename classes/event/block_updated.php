<?php
namespace block_textblock_to_all_courses\event;

defined('MOODLE_INTERNAL') || die();

class block_updated extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'block_textblock_to_all_courses';
    }

    public static function get_name() {
        return get_string('eventblockupdated', 'block_textblock_to_all_courses');
    }

    public function get_description() {
        return "The user with id '{$this->userid}' updated the text block with id '{$this->objectid}'.";
    }

    public function get_url() {
        return new \moodle_url('/blocks/textblock_to_all_courses/index.php');
    }
}
