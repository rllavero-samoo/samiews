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
 * Services declaration.
 *
 * @package    SAMIE
 * @copyright  2015 Planificacion de Entornos Tecnologicos SL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
        'local_samiews_get_chat_interventions' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_chat_interventions',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return interventions for a chat.',
                'type'        => 'read',
        ),
        'local_samiews_get_forum_interventions' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_forum_interventions',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return interventions for a forum.',
                'type'        => 'read',
        ),
        'local_samiews_get_privatemessages_interventions' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_privatemessages_interventions',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return interventions for a private messages.',
                'type'        => 'read',
        ),
        'local_samiews_get_wikis_interventions' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_wikis_interventions',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return interventions for a wikis comments.',
                'type'        => 'read',
        ),
        'local_samiews_get_attendance_log' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_attendance_log',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return actions of users based in the log table',
                'type'        => 'read',
        ),
        'local_samiews_get_gradebook' => array(
                'classname'   => 'local_samiews_external',
                'methodname'  => 'get_gradebook',
                'classpath'   => 'local/samiews/externallib.php',
                'description' => 'Return final evaluation module results.',
                'type'        => 'read',
        )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'WS_SAMIE' => array(
                'functions' => array (
                    'local_samiews_get_chat_interventions',
                    'local_samiews_get_forum_interventions',
                    'local_samiews_get_privatemessages_interventions',
                    'local_samiews_get_wikis_interventions',
                    'local_samiews_get_attendance_log',
                    'local_samiews_get_gradebook',
                ),
                'restrictedusers' => 0,
                'enabled' => 1
        )
);
