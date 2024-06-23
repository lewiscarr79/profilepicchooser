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


defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_profilepicchooser';

$plugin->version = 2024062211;  // Increment this

$plugin->requires = 2023100900; // This is for Moodle 4.3
$plugin->maturity = MATURITY_ALPHA;
$plugin->release = 'v0.1';