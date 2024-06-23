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
// Turn on error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary Moodle configuration and library files
require_once('../../config.php');
require_once($CFG->libdir.'/gdlib.php');
require_once($CFG->dirroot.'/user/lib.php');

// Ensure the user is logged in
require_login();

// Get the image URL from the request parameters
$imageurl = required_param('imageurl', PARAM_URL);

try {
    // Check if the user has permission to edit their own profile
    require_capability('moodle/user:editownprofile', context_system::instance());

    // Get the user's context
    $context = context_user::instance($USER->id);

    // Create a temporary file to store the downloaded image
    $tempfile = tempnam(sys_get_temp_dir(), 'profile_pic');
    if (file_put_contents($tempfile, file_get_contents($imageurl)) === false) {
        throw new Exception('Failed to save image to temp file');
    }

    // Process the image and create a new icon
    $newpicture = process_new_icon($context, 'user', 'icon', 0, $tempfile);
    if (!$newpicture) {
        throw new Exception('Failed to process new icon');
    }

    // Update the user's picture field in the database
    try {
        if (!$DB->set_field('user', 'picture', $newpicture, array('id' => $USER->id))) {
            throw new Exception('Database update failed');
        }
    } catch (Exception $e) {
        throw new Exception('Failed to update user picture in database: ' . $e->getMessage());
    }

    // Remove the temporary file
    unlink($tempfile);

    // Reset the page theme and output to reflect changes
    $PAGE->reset_theme_and_output();

    // Clear user sessions to force a refresh of user data
    try {
        \core\session\manager::kill_user_sessions($USER->id, session_id());
    } catch (Exception $e) {
        error_log('Error killing user sessions: ' . $e->getMessage());
    }

    // Reload the user's information from the database
    $USER = $DB->get_record('user', array('id' => $USER->id));

    // Trigger an event to notify the system that the user profile has been updated
    try {
        \core\event\user_updated::create_from_userid($USER->id)->trigger();
    } catch (Exception $e) {
        error_log('Error triggering user updated event: ' . $e->getMessage());
    }

    // Return a JSON response indicating success
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // If an error occurs, return a JSON response with error details
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage(), 
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}