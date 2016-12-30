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
 * Access capabilities.
 *
 * @package    SAMIE
 * @copyright  2015 Planificacion de Entornos Tecnologicos SL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/samiews:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'guest'          => 'CAP_DENY',
            'student'        => 'CAP_DENY',
            'teacher'        => 'CAP_DENY',
            'editingteacher' => 'CAP_DENY',
            'manager'        => 'CAP_DENY'
        )
    ),
    'local/samiews:addinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'guest'          => 'CAP_DENY',
            'student'        => 'CAP_DENY',
            'teacher'        => 'CAP_DENY',
            'editingteacher' => 'CAP_DENY',
            'manager'        => 'CAP_DENY'
        )
    ),
    'local/samiews:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'guest'          => 'CAP_DENY',
            'student'        => 'CAP_DENY',
            'teacher'        => 'CAP_DENY',
            'editingteacher' => 'CAP_DENY',
            'manager'        => 'CAP_ALLOW'
        )
    ),
);