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
 * Abstract class for formatting reminder message based on activity type.
 *
 * @package    local_reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class local_reminder_activity_handler {
    /**
     * This function will format/append reminder messages with necessary info
     * based on constraints in that activity instance.
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
        // Do nothing.
    }

    /**
     * Returns associated description of the given activity.
     *
     * @param object $activity activity instance
     * @param object $event event instance
     * @return string description related to this activity.
     */
    abstract public function get_description($activity, $event);

    /**
     * Filter out users who still does not have completed this activity.
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
        return $users;
    }

    /**
     * Formats given date and time based on given user's timezone.
     *
     * @param number $datetime epoch time.
     * @param object $user user to format for.
     * @param object $reminder reminder reference.
     * @return string formatted date time according to give user.
     */
    protected function format_datetime($datetime, $user, $reminder) {
        $tzone = 99;
        if (isset($user) && !empty($user)) {
            $tzone = reminders_get_timezone($user);
        }

        $daytimeformat = get_string('strftimedaydate', 'langconfig');
        $utimeformat = get_correct_timeformat_user($user);
        return userdate($datetime, $daytimeformat, $tzone) .
            ' ' . userdate($datetime, $utimeformat, $tzone) .
            ' &nbsp;&nbsp;<span style="' . $reminder->tzshowstyle . '">' .
            local_reminders_tz_info::get_human_readable_tz($tzone) . '</span>';
    }

    /**
     * Returns completion status for the given course module of the user id.
     *
     * @param object $course course instance.
     * @param object $coursemodule course module instance.
     * @param int $userid user id.
     * @return bool true if completed. false otherwise.
     */
    protected function check_completion_status($course, $coursemodule, $userid) {
        $completion = new completion_info($course);
        if ($completion->is_enabled($coursemodule)) {
            return $completion->get_data($coursemodule, false, $userid)->completionstate;
        }
        return false;
    }
}

require_once(__DIR__ . '/local_reminder_generic_handler.class.php');
require_once(__DIR__ . '/local_reminder_quiz_handler.class.php');
require_once(__DIR__ . '/local_reminder_assign_handler.class.php');
require_once(__DIR__ . '/local_reminder_choice_handler.class.php');
require_once(__DIR__ . '/local_reminder_feedback_handler.class.php');
require_once(__DIR__ . '/local_reminder_lesson_handler.class.php');
require_once(__DIR__ . '/local_reminder_survey_handler.class.php');
require_once(__DIR__ . '/local_reminder_resource_handler.class.php');
