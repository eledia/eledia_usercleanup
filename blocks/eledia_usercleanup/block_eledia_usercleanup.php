<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block Definition. The Block is used to configure the plugin and defines the cron.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_eledia_usercleanup extends block_base {

    public function init() {
        $this->title   = get_string('title', 'block_eledia_usercleanup');
    }

    public function applicable_formats() {
        return array('site' => true);
    }

    public function get_content() {
        global $USER, $CFG, $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        if (has_capability('moodle/site:config', CONTEXT_SYSTEM::instance())) {
            $url = new moodle_url('/admin/settings.php', array('section' => 'blocksettingeledia_usercleanup'));
            $this->content->text .= '<div>';
            $this->content->text .= '<a class="btn" href="'.$url->out().'">';
            $this->content->text .= get_string('el_header', 'block_eledia_usercleanup');
            $this->content->text .= '</a>';
            $this->content->text .= '</div>';
        }
        return $this->content;
    }

    public function has_config() {
        return true;
    }
}
