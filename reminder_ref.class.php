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
 * Local helper functions for reminders cron function.
 *
 * @package    local_reminders
 * @author     Isuru Weerarathna <uisurumadushanka89@gmail.com>
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

/**
 * Reminder reference class.
 *
 * @package    local_reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reminder_ref {
    /**
     * created reminder reference.
     *
     * @var local_reminder
     */
    protected $reminder;
    /**
     * Array of users to send this reminder.
     *
     * @var array
     */
    protected $sendusers;

    /**
     * Creates new reminder reference.
     *
     * @param local_reminder $reminder created reminder.
     * @param array $sendusers array of users.
     */
    public function __construct($reminder, $sendusers) {
        $this->reminder = $reminder;
        $this->sendusers = $sendusers;
    }

    /**
     * Returns total number of users eligible to send this reminder.
     *
     * @return int total number of users.
     */
    public function get_total_users_to_send() {
        return count($this->sendusers);
    }

    /**
     * Returns the ultimate notification event instance to send for given user.
     *
     * @param object $fromuser from user.
     * @param object $touser user to send.
     * @return object new notification instance.
     */
    public function get_event_to_send($fromuser, $touser) {
        return $this->reminder->get_sending_event($fromuser, $touser);
    }

    /**
     * Returns the notification event instance based on change type.
     *
     * @param string $changetype change type PRE|OVERDUE.
     * @param object $fromuser from user.
     * @param object $touser user to send.
     * @param stdClass $ctxinfo additional context info needed to process.
     * @return object new notification instance.
     */
    public function get_updating_send_event($changetype, $fromuser, $touser, $ctxinfo) {
        return $this->reminder->get_updating_event_message($changetype, $fromuser, $touser, $ctxinfo);
    }

    /**
     * Returns eligible sending users as array.
     *
     * @return array users eligible to receive message.
     */
    public function get_sending_users() {
        return $this->sendusers;
    }

    /**
     * Cleanup the reminder memory.
     *
     * @return void nothing.
     */
    public function cleanup() {
        unset($this->sendusers);
        if (isset($this->reminder)) {
            $this->reminder->cleanup();
        }
    }
}
