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
 * Definición de funciones externas y servicios preconfigurados de local_user.
 *
 * @package    local_user
 * @copyright  2019 Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Funciones de servicio web expuestas por el plugin.
$functions = [
    'local_user_get_roles' => [
        'classname' => 'local_user\external\get_roles',
        'methodname' => 'execute',
        'description' => 'Return list of roles',
        'type' => 'read',
        'capabilities' => 'moodle/role:manage',
    ],
];

// Servicios preconfigurados instalados automáticamente con el plugin.
$services = [
    'userservice' => [
        'functions' => ['local_user_get_roles'],
        'restrictedusers' => 0,
        'enabled' => 1,
    ],
];
