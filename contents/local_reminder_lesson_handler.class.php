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
 * Supports Lesson module related information.
 *
 * @package    local_reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_reminder_lesson_handler extends local_reminder_activity_handler {
    /**
     * Filter out users who still does not have completed lesson activity.
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
        global $CFG;
        require_once($CFG->dirroot . '/mod/lesson/lib.php');

        $filteredusers = [];
        foreach ($users as $auser) {
            $cansubmit = has_capability('mod/lesson:view', $coursemodulecontext, $auser);
            if (!$cansubmit) {
                continue;
            }
            $status = lesson_get_completion_state($course, $coursemodule, $auser->id, false);
            if (!$status) {
                $filteredusers[] = $auser;
            }
        }
        return $filteredusers;
    }

    /**
     * Appends lesson time limit into the email.
     *
     * @param string $htmlmail email content.
     * @param string $modulename module name as 'lesson'.
     * @param object $activity lesson instance.
     * @param object $user user to prepare the message for.
     * @param object $event event instance.
     * @param object $reminder reminder reference.
     * @return void nothing.
     */
    public function append_info(&$htmlmail, $modulename, $activity, $user = null, $event = null, $reminder = null) {
        if (isset($activity->timelimit) && $activity->timelimit > 0) {
            $htmlmail .= $reminder->write_table_row(
                get_string('timelimit', 'lesson'),
                format_time($activity->timelimit)
            );
        }
    }

    /**
     * Return feedback intro field as description.
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
