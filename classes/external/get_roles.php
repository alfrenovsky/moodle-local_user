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
 * Función externa para obtener el listado de roles de Moodle.
 *
 * @package    local_user
 * @copyright  2019 Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_user\external;

use context_system;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

defined('MOODLE_INTERNAL') || die();

if (!class_exists(external_api::class)) {
    // Compatibilidad con Moodle 3.11 a 4.1 donde las clases externas aún son globales.
    global $CFG;
    require_once($CFG->libdir . '/externallib.php');
    class_alias(\external_api::class, external_api::class);
    class_alias(\external_function_parameters::class, external_function_parameters::class);
    class_alias(\external_multiple_structure::class, external_multiple_structure::class);
    class_alias(\external_single_structure::class, external_single_structure::class);
    class_alias(\external_value::class, external_value::class);
}

/**
 * Servicio externo para listar los roles del sitio.
 *
 * @package    local_user
 * @copyright  2019 Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_roles extends external_api {

    /**
     * Define los parámetros de entrada de execute().
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Devuelve el listado de roles configurados en Moodle.
     *
     * @return array Lista de roles con id, localname, shortname y description.
     */
    public static function execute(): array {
        // Valida parámetros y contexto del sistema antes de verificar permisos.
        self::validate_parameters(self::execute_parameters(), []);

        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);
        require_capability('moodle/role:manage', $systemcontext);

        $roles = role_fix_names(get_all_roles(), $systemcontext, ROLENAME_ORIGINAL);
        $result = [];

        foreach ($roles as $role) {
            $result[] = [
                'id' => (int) $role->id,
                'localname' => $role->localname,
                'shortname' => $role->shortname,
                'description' => role_get_description($role),
            ];
        }

        return $result;
    }

    /**
     * Define la estructura del valor de retorno de execute().
     *
     * @return external_multiple_structure
     */
    public static function execute_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'id' => new external_value(PARAM_INT, 'ID del rol'),
                    'localname' => new external_value(PARAM_TEXT, 'Nombre local del rol'),
                    'shortname' => new external_value(PARAM_TEXT, 'Nombre corto del rol'),
                    'description' => new external_value(PARAM_RAW, 'Descripción del rol', VALUE_OPTIONAL),
                ],
                'Lista de roles'
            )
        );
    }
}

