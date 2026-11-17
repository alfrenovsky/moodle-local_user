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
 * Pruebas unitarias para la función externa local_user_get_roles.
 *
 * @package    local_user
 * @copyright  2019 Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_user\external;

use core_external\tests\externallib_testcase;
use required_capability_exception;

defined('MOODLE_INTERNAL') || die();

if (!class_exists(externallib_testcase::class)) {
    // Compatibilidad con Moodle 3.11 a 4.1 donde externallib_testcase es global.
    global $CFG;
    require_once($CFG->dirroot . '/webservice/tests/externallib_testcase.php');
    class_alias(\externallib_testcase::class, externallib_testcase::class);
}

/**
 * Casos de prueba para local_user\external\get_roles.
 *
 * @package    local_user
 * @copyright  2019 Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class get_roles_test extends externallib_testcase {

    /**
     * Verifica que un usuario administrador o con permiso moodle/role:manage obtenga los roles.
     *
     * @covers \local_user\external\get_roles::execute
     */
    public function test_execute_authorized(): void {
        $this->resetAfterTest(true);
        $this->setAdminUser();

        $roles = get_roles::execute();
        $roles = get_roles::clean_returnvalue(get_roles::execute_returns(), $roles);

        $this->assertNotEmpty($roles);
        $first = reset($roles);
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('localname', $first);
        $this->assertArrayHasKey('shortname', $first);
    }

    /**
     * Verifica que un usuario sin permiso moodle/role:manage reciba una excepción.
     *
     * @covers \local_user\external\get_roles::execute
     */
    public function test_execute_unauthorized(): void {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->expectException(required_capability_exception::class);
        get_roles::execute();
    }
}
