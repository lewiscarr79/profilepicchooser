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

// Require the Moodle configuration file
require_once('../../config.php');

// Ensure the user is logged in
require_login();

// Set the page URL
$PAGE->set_url(new moodle_url('/blocks/profilepicchooser/change.php'));

// Set the context to the system context (global)
$PAGE->set_context(context_system::instance());

// Initialize an array to store image URLs
$images = array();

// Set the directory path where profile pictures are stored
$directory = $CFG->dirroot . '/blocks/profilepicchooser/profilepics/';

// Get a list of all files in the directory
$files = scandir($directory);

// Loop through each file in the directory
foreach ($files as $file) {
    // Check if the file is an image (jpg, jpeg, png, or gif)
    if (in_array(pathinfo($file, PATHINFO_EXTENSION), array('jpg', 'jpeg', 'png', 'gif'))) {
        // If it's an image, add its URL to the $images array
        $images[] = (new moodle_url('/blocks/profilepicchooser/profilepics/' . $file))->out();
    }
}

// Set the content type of the response to JSON
header('Content-Type: application/json');

// Output the array of image URLs as a JSON-encoded string
echo json_encode($images);