<?php

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
 * External Web Service Template
 *
 * @package    localuser
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_user_external extends external_api {

    /**
     * Returns description of get_roles() parameters.
     *
     * @return external_function_parameters
     * @since Moodle 2.5
     */
    public static function get_roles_parameters() {
		return new external_function_parameters(
            array(
               //if I had any parameters, they would be described here. But I don't have any, so this array is empty.
            )
        );
    }

    /**
     * Get roles
     *
     * @return array list of roles
     * @since Moodle 2.3
     */
    public static function get_roles() {
        
		// Check access permissions.
		$systemcontext = context_system::instance();
		require_capability('moodle/role:manage', $systemcontext);
		
		// Get some basic data we are going to need.
		$roles = role_fix_names(get_all_roles(), $systemcontext, ROLENAME_ORIGINAL);
		
		$json = [];
		
		foreach ($roles as $role) {
			
			$json[] = [
				'id'			=> $role->id,
				'localname'		=> $role->localname,
				'shortname'		=> $role->shortname,
				'description'	=> utf8_encode(role_get_description($role)),
			];
			
		}
		
		return $json;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.3
     */
    public static function get_roles_returns() {
		
		return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id'			=> new external_value(PARAM_INT, 'Rol id'),
					'localname'		=> new external_value(PARAM_TEXT, 'Rol localname'),
					'shortname'		=> new external_value(PARAM_TEXT, 'Rol shortname'),
					'description'	=> new external_value(PARAM_RAW, 'Rol description', VALUE_OPTIONAL),
                ), 'List of roles'
            )
        );
		
	}

}
