<?php
class block_textblock_to_all_courses extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_textblock_to_all_courses');
    }

    public function get_content() {
        global $DB, $COURSE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        $blocks = $DB->get_records('block_textblock_to_all', ['enabled' => 1]);
        
        foreach ($blocks as $block) {
            if ($this->should_display_block($block, $COURSE->id, $USER->id)) {
                $this->content->text .= $block->content;
            }
        }

        return $this->content;
    }

    private function should_display_block($block, $courseid, $userid) {
        // Verificar si el bloque debe mostrarse en este curso
        $courses = !empty($block->courses) ? json_decode($block->courses) : null;
        if (!empty($courses) && !in_array($courseid, $courses)) {
            return false;
        }

        // Verificar roles restringidos
        $restricted_roles = !empty($block->restricted_roles) ? json_decode($block->restricted_roles) : [];
        if (!empty($restricted_roles)) {
            $user_roles = get_user_roles(context_course::instance($courseid), $userid);
            foreach ($user_roles as $role) {
                if (in_array($role->roleid, $restricted_roles)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function has_config() {
        return false; // Cambiado a false porque no usamos config_instance
    }

    public function instance_allow_config() {
        return false; // Los bloques individuales no son configurables
    }

    public function applicable_formats() {
        return array(
            'all' => true,
            'site' => true,
            'course' => true,
            'my' => true
        );
    }
}
