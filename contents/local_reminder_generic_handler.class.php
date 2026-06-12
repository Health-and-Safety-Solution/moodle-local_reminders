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
 * All avitivity specific classes required for specific handling.
 *
 * @package    local_reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Supports generic activity completion using Moodle's completion_info API.
 *
 * This class will be used when no specific handler being implemented for the given
 * module. And also will gracefully failed, if filtering cannot be fulfilled.
 *
 * @package    local_reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_reminder_generic_handler extends local_reminder_activity_handler {
    /**
     * Filter out users who still does not have completed this module using Moodle core completion API.
     *
     * @param array $users user array to check.
     * @param string $type reminder call type PRE|POST.
     * @param object $activity activity instance.
     * @param object $course course instance belong to.
     * @param object $coursemodule course module instance.
     * @param object $coursemodulecontext course module context instance.
     * @return array array of filtered users.
     */
    public function filter_authorized_users($users, $type, $activity, $course, $coursemodule, $coursemodulecontext) {
        $filteredusers = [];
        foreach ($users as $auser) {
            $status = $this->check_completion_status($course, $coursemodule, $auser->id);
            if (!$status) {
                $filteredusers[] = $auser;
            }
        }
        return $filteredusers;
    }

    /**
     * Return survey intro field as description.
     *
     * @param object $activity activity instance
     * @param object $event event instance
     * @return string description related to this activity.
     */
    public function get_description($activity, $event) {
        if (isset($activity->intro)) {
            return $activity->intro;
        }
        return null;
    }
}
