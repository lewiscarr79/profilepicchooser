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
 * Version information. When a new version is released the version is incremented
 *
 * @package    block_profilepicchooser
 * @copyright  2024 Lewis Carr adaptiVLE Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Ensure this file is being included by a Moodle script
defined('MOODLE_INTERNAL') || die();

// Define the block class, extending the base Moodle block class
class block_profilepicchooser extends block_base {
    
    // Initialize the block
    public function init() {
        // Set the block title using a language string
        $this->title = get_string('pluginname', 'block_profilepicchooser');
    }

    // Generate and return the block's content
    public function get_content() {
        global $OUTPUT, $PAGE;

        // If content has already been generated, return it
        if ($this->content !== null) {
            return $this->content;
        }

        // Initialize the content object
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        // Create a URL for the change picture page
        $changeurl = new moodle_url('/blocks/profilepicchooser/change.php');

        // Start a centered div
        $this->content->text .= html_writer::start_div('text-center');

        // Add a button to change the profile picture
        $this->content->text .= html_writer::tag('button', 
            get_string('changepicture', 'block_profilepicchooser'), 
            array(
                'id' => 'profilepicchooser-btn', 
                'class' => 'btn btn-primary mt-2',
                'aria-haspopup' => 'dialog' // Accessibility attribute
            )
        );

        // Close the centered div
        $this->content->text .= html_writer::end_div();

        // Include CSS file
        $PAGE->requires->css('/blocks/profilepicchooser/styles.css');

        // Include JavaScript file
        $PAGE->requires->js('/blocks/profilepicchooser/module.js');

        // Initialize JavaScript with the change URL
        $PAGE->requires->js_init_call('M.block_profilepicchooser.init', array($changeurl->out()));

        // Return the generated content
        return $this->content;
    }

    // Specify where this block can be used
    public function applicable_formats() {
        // Allow this block on all page formats
        return array('all' => true);
    }
}