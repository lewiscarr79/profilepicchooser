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

/**
 * Handles file serving for the profilepicchooser block
 *
 * @param stdClass $course The course object
 * @param stdClass $cm The course module object
 * @param stdClass $context The context object
 * @param string $filearea The name of the file area
 * @param array $args Extra arguments
 * @param bool $forcedownload Whether or not to force the download of the file
 * @param array $options Additional options
 * @return bool False if file not found, does not return if file is found (sends file)
 */
function block_profilepicchooser_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check if the context is at the system level
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    // Ensure the user is logged in
    require_login();

    // Check if the file area is 'profilepics'
    if ($filearea !== 'profilepics') {
        return false;
    }

    // Construct the relative path from the provided arguments
    $relativepath = implode('/', $args);

    // Construct the full path to the requested file
    $fullpath = "{$CFG->dirroot}/blocks/profilepicchooser/profilepics/{$relativepath}";

    // Check if the file exists
    if (!file_exists($fullpath)) {
        return false;
    }

    // Send the file to the user
    send_file($fullpath, basename($fullpath));
}