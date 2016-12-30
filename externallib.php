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
 * Webservice implementation.
 *
 * @package    SAMIE
 * @copyright  2015 Planificacion de Entornos Tecnologicos SL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_samiews_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_chat_interventions_parameters() {
        return new external_function_parameters(
            array(
                'courses' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of courses',
                    VALUE_DEFAULT)),
                'users' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of users',
                    VALUE_DEFAULT)),
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_forum_interventions_parameters() {
        return new external_function_parameters(
            array(
                'courses' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of courses',
                    VALUE_DEFAULT)),
                'users' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of users',
                    VALUE_DEFAULT)),
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_privatemessages_interventions_parameters() {
        return new external_function_parameters(
            array(
                'courses' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of courses',
                    VALUE_DEFAULT)),
                'users' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of users',
                    VALUE_DEFAULT)),
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_wikis_interventions_parameters() {
        return new external_function_parameters(
            array(
                'courses' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of courses',
                    VALUE_DEFAULT)),
                'users' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of users',
                    VALUE_DEFAULT)),
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_attendance_log_parameters() {
        return new external_function_parameters(
            array(
                'courses' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of courses',
                    VALUE_DEFAULT)),
                'users' => new external_multiple_structure(new external_value(PARAM_INT, 'Array with the id of users',
                    VALUE_DEFAULT)),
                    // The next 2 parameters that can receive, will have the format dd/mm/yyyy. Will not clean nothing.
                'startdate' => new external_value(PARAM_RAW, 'Filter date, start date', VALUE_DEFAULT),
                'enddate' => new external_value(PARAM_RAW, 'Filter date, end date', VALUE_DEFAULT),
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_gradebook_parameters() {
        return new external_function_parameters(
            array(

            )
        );
    }

    /**
     * Returns gradebook results
     * @return string json with the gradebook
     */
    public static function get_gradebook() {
        global $USER, $DB;

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('moodle/grade:viewall', $context)) {
            throw new moodle_exception('cannotviewallgrade');
        }

        $sql = "SELECT GG.id as gg_id, (SELECT fullname
                                          FROM {grade_categories}
                                         WHERE id = GI.iteminstance) AS gi_subcategoryname,
                       GG.itemid as gg_itemid, GG.rawgrade as gg_rawgrade, GG.rawgrademax as gg_rawgrademax,
                       GG.rawgrademin as gg_rawgrademin, GG.finalgrade as gg_finalgrade, GG.userid as gg_userid, GI.id as gi_id,
                       GI.courseid as gi_courseid, GI.categoryid as gi_category_id, GI.itemname as gi_itemname,
                       GI.itemtype as gi_itemtype, GI.itemmodule as gi_itemmodule, GI.iteminstance as gi_iteminstance,
                       GI.grademax as gi_grademax, GI.grademin as gi_grademin, GC.id as gc_id, GC.courseid as gc_courseid,
                       GC.parent as gc_parent, GC.fullname as gc_fullname, COURSE.id as course_id,
                       COURSE.fullname as course_fullname, COURSE.shortname as course_shortname, COURSE.category as course_category,
                       COURSE.idnumber as course_idnumber
                  FROM {grade_grades} GG
            INNER JOIN {grade_items} GI ON GG.itemid = GI.id
             LEFT JOIN {grade_categories} GC ON GC.id = GI.categoryid
            INNER JOIN {course} COURSE ON COURSE.id = GI.courseid";

        $records = $DB->get_records_sql($sql);
        return json_encode($records);
    }

    /**
     * Returns chat interventions
     * @return string json of result
     */
    public static function get_chat_interventions($courses = array(), $users = array()) {
        global $USER, $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::get_chat_interventions_parameters(),
                array('courses' => $courses, 'users' => $users));

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('mod/chat:readlog', $context)) {
            throw new moodle_exception('cannotreadlog');
        }

        $sql = "SELECT CHATM.id as chatid, CHATM.userid AS id, CHAT.course as courseid, CHATM.message AS chatmessage,
                       CHATM.timestamp, CHATM.timestamp, C.fullname AS course
                  FROM {chat_messages} CHATM
            INNER JOIN {chat} CHAT ON CHATM.chatid = CHAT.id
            INNER JOIN {course} C ON C.id = CHAT.course";

        $inparams = array();
        $inparams2 = array();
        if ($courses != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
            $sql .= " AND CHAT.course {$insql} ";
        }
        if ($users != null) {
            list($insql2, $inparams2) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);
            $sql .= " AND CHATM.userid {$insql2} ";
        }
        $sql .= " ORDER BY id,timestamp";

        $params = array_merge($inparams, $inparams2);
        $records = $DB->get_records_sql($sql, $params);

        $interventions = 0;
        $lastmessage = "";
        $countertime = 0;
        foreach ($records as $row) {
            if ($row->chatmessage == 'enter') {
                if ($lastmessage == "enter") {
                    // If the last user(distinct of this) don't close the chat, we add a third.
                    $countertime += 1200;
                }
                $actualuser = $row->id;
                $actualcourse = $row->course;
                $entertime = $row->timestamp; // Timestamp.
            } else if ($row->chatmessage == 'exit') {
                if ($row->id == $actualuser) {
                    if (($row->timestamp - $entertime) > 3600) {
                        // Check if the session is done.
                        $countertime += 1200; // 20 min in sec.
                    } else {
                        $countertime += ($row->timestamp - $entertime);
                    }
                }
            } else if ($row->chatmessage == 'Loading...') {
                // We don't care about this messages.
                continue;
            } else {
                $interventions++;
            }
            $lastmessage = $row->chatmessage;
        }
        return json_encode(array('interventions' => $interventions, 'countertime' => $countertime));
    }

    /**
     * Returns forum interventions
     * @return string json of result
     */
    public static function get_forum_interventions($courses = array(), $users = array()) {
        global $USER, $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::get_forum_interventions_parameters(),
                array('courses' => $courses, 'users' => $users));

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('mod/forum:viewdiscussion', $context)) {
            throw new moodle_exception('cannotviewdiscussion');
        }

        $sql = "    SELECT COUNT(*) AS posts
                      FROM {forum_posts} FP
                INNER JOIN {forum_discussions} FD ON FD.id = FP.discussion
                INNER JOIN {course} C ON FD.course = C.id
                INNER JOIN {user} U ON U.id = FP.userid
                     WHERE 1 = 1 ";

        $inparams = array();
        $inparams2 = array();
        if ($courses != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
            $sql .= " AND C.id {$insql} ";
        }
        if ($users != null) {
            list($insql2, $inparams2) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);
            $sql .= " AND U.id {$insql2} ";
        }
        $params = array_merge($inparams, $inparams2);
        $records = $DB->count_records_sql($sql, $params);
        return $records;
    }

    /**
     * Returns private messages interventions
     * @return string json of result
     */
    public static function get_privatemessages_interventions($courses = array(), $users = array()) {
        global $USER, $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::get_privatemessages_interventions_parameters(),
                array('courses' => $courses, 'users' => $users));

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('moodle/site:readallmessages', $context)) {
            throw new moodle_exception('cannotreadallmessages');
        }

        $filter = "";
        $inparams = array();
        $inparams2 = array();
        if ($courses != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
            $filter .= " AND E.courseid {$insql}";
        }
        if ($users != null) {
            list($insql2, $inparams2) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);
            $filter .= " AND UE.userid {$insql2}";
        }

        $sql = "SELECT COUNT(*) AS messages
                  FROM (
                           SELECT DISTINCT M.*
                             FROM {message} M
                       INNER JOIN {user_enrolments} UE ON UE.userid = M.useridfrom
                       INNER JOIN {enrol} E ON UE.enrolid = E.id
                            WHERE 1 = 1
                       {$filter}
                         GROUP BY id
                  ) DATA ";

        $params = array_merge($inparams, $inparams2);
        $records = $DB->count_records_sql($sql, $params);
        return $records;
    }

    /**
     * Returns wikis interventions
     * @return string json of result
     */
    public static function get_wikis_interventions($courses = array(), $users = array()) {
        global $USER, $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::get_wikis_interventions_parameters(),
                array('courses' => $courses, 'users' => $users));

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('moodle/comment:view', $context)) {
            throw new moodle_exception('cannotviewcomment');
        }

        $sql = "SELECT COUNT(*)
                  FROM {comments} COM
            INNER JOIN {context} CTX ON COM.contextid = CTX.id
            INNER JOIN {course_modules} CM ON CTX.instanceid = CM.id
            INNER JOIN {wiki} WIK ON CM.instance = WIK.id
                 WHERE 1 = 1 ";

        $inparams = array();
        $inparams2 = array();
        if ($courses != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
            $sql .= " AND WIK.course {$insql} ";
        }
        if ($users != null) {
            list($insql2, $inparams2) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);
            $sql .= " AND COM.userid {$insql2} ";
        }

        $params = array_merge($inparams, $inparams2);
        $records = $DB->count_records_sql($sql, $params);
        return $records;
    }

    /**
     * Returns attendance log
     * @return string json of result
     */
    public static function get_attendance_log($courses = array(), $users = array(), $category) {
        global $USER, $DB;

        // Parameter validation.
        $params = self::validate_parameters(self::get_attendance_log_parameters(),
                array('courses' => $courses, 'users' => $users, 'startdate' => $startdate, 'enddate' => $enddate));

        // Context validation.
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        // Capability checking.
        if (!has_capability('report/log:view', $context)) {
            throw new moodle_exception('cannotviewlog');
        }

        $sql = "SELECT log.id, COALESCE(objecttable, log.target) AS service, log.action, log.userid, log.timecreated, log.courseid
                 FROM {logstore_standard_log} log
                 WHERE 1 = 1 ";
        $inparams = array();
        $inparams2 = array();
        if ($courses != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
            $sql .= " AND log.courseid {$insql} ";
        }
        if ($users != null) {
            list($insql2, $inparams2) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);
            $sql .= " AND log.userid {$insql2} ";
        }
        if ($startdate != '') {
            $sql .= " AND (from_unixtime(log.timecreated)) >= str_to_date('$startdate', '%d/%m/%Y') ";
        }
        if ($enddate != '') {
            $sql .= " AND (from_unixtime(log.timecreated)) <= str_to_date('$enddate 23:59:59', '%d/%m/%Y %H:%i:%s') ";
        }
        $sql .= " ORDER BY log.id ";
        $params = array_merge($inparams, $inparams2);

        $records = $DB->get_records_sql($sql, $params);
        return json_encode($records);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_gradebook_returns() {
        // Return a string with json format.
        return new external_value(PARAM_RAW, 'Dataset of gradebook');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_chat_interventions_returns() {
        // Return a string with json format.
        return new external_value(PARAM_RAW, 'Interventions results of chat');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_forum_interventions_returns() {
        return new external_value(PARAM_INT, 'Posts results of forums');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_privatemessages_interventions_returns() {
        return new external_value(PARAM_INT, 'Interventions results of private messages');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_wikis_interventions_returns() {
        return new external_value(PARAM_INT, 'Interventions results of wikis');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_attendance_log_returns() {
        // Return a string with json format.
        return new external_value(PARAM_RAW, 'Dataset of log table');
    }

}
