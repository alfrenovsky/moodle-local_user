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
 * Cliente REST/JSON de ejemplo para Moodle (3.11 a 5.3) - local_user.
 *
 * Este script no depende del núcleo de Moodle y puede ejecutarse por CLI o navegador.
 *
 * @package    local_user
 * @author     Leonel Leonardis
 * @copyright  2026 Alfrenovsky
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Pasos de configuración en Moodle:
// 1- Instalar el plugin.
// 2- Habilitar los servicios web (Administración del sitio > Características avanzadas).
// 3- Habilitar el protocolo REST (Administración del sitio > Servidor > Servicios web > Gestionar protocolos).
// 4- Crear un token para un usuario con permiso moodle/role:manage y el servicio 'userservice'.
// 5- Configurar $token y $domainname abajo y ejecutar este script.

$token = 'YOUR_TOKEN';
$domainname = 'http://YOURMOODLE';
$functionname = 'local_user_get_roles';

$serverurl = $domainname . '/webservice/rest/server.php?' . http_build_query([
    'wstoken' => $token,
    'wsfunction' => $functionname,
    'moodlewsrestformat' => 'json',
]);

$ch = curl_init($serverurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');

$response = curl_exec($ch);
if ($response === false) {
    fwrite(STDERR, 'Error de cURL: ' . curl_error($ch) . PHP_EOL);
    exit(1);
}

$decoded = json_decode($response, true);
if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}
print_r($decoded !== null ? $decoded : $response);
