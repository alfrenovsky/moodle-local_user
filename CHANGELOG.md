# Historial de cambios

## [2.0.0] - 2026-11-17

Actualización completa del plugin para garantizar compatibilidad con **Moodle 3.11 a 5.3** y **PHP 7.3 a 8.4+**, partiendo de la versión original desarrollada para Moodle 3.x.

### Qué se corrigió y por qué

1. **Migración de la API externa (`externallib.php` → `classes/external/get_roles.php`):**
   - Desde Moodle 4.2, el uso de `lib/externallib.php` y la clase global `\external_api` está deprecado en favor de clases autocargables bajo `\core_external\*`.
   - Se movió la implementación a `classes/external/get_roles.php` (`local_user\external\get_roles`) con los métodos estándar `execute_parameters()`, `execute()` y `execute_returns()`, eliminando `'classpath'` de `db/services.php` y borrando `externallib.php`.
   - Para mantener compatibilidad con **Moodle 3.11 a 4.1** sin usar la API deprecada en **Moodle 4.2 a 5.3**, se agregó un bloque condicional `if (!class_exists(external_api::class))` que solo en versiones `< 4.2` carga `$CFG->libdir . '/externallib.php'` y crea alias (`class_alias`) hacia `core_external\*`. En Moodle 4.2+ ese bloque no se ejecuta y se usa la API nativa.
   - Se incorporaron `self::validate_parameters()` y `self::validate_context($systemcontext)` junto a `require_capability('moodle/role:manage', $systemcontext)`.

2. **Reemplazo del cliente XML-RPC por REST + JSON (`client/client.php`):**
   - La extensión `php-xmlrpc` fue eliminada del núcleo de PHP en PHP 8.0 y el protocolo `webservice_xmlrpc` fue retirado del núcleo de Moodle en Moodle 4.1. Además, `client/client.php` mezclaba `xmlrpc_encode_request()`/`xmlrpc_decode()` con la URL del endpoint REST.
   - Se reescribió `client/client.php` para consumir `/webservice/rest/server.php` con `moodlewsrestformat=json` usando cURL nativo de PHP (sin `curl_close()`, deprecado en PHP 8.5) y `json_decode()`, eliminando el wrapper heredado `client/curl.php` y el token fijo de ejemplo.

3. **Cumplimiento de la Privacy API (`classes/privacy/provider.php`):**
   - Moodle exige declarar cómo maneja datos personales cada plugin. Dado que `local_user` no almacena datos de usuarios, se implementó `\core_privacy\local\metadata\null_provider` y la cadena `privacy:metadata` en `lang/en/local_user.php`.

4. **Metadatos y limpieza en `version.php` y `db/services.php`:**
   - Se eliminó `$plugin->cron = 0;` (deprecado en favor de la Task API).
   - Se estableció `$plugin->requires = 2021051700;` (Moodle 3.11) y `$plugin->supported = [311, 503];`.
   - Se agregaron las guardas `defined('MOODLE_INTERNAL') || die();` en todos los archivos PHP cargados por Moodle.
   - Se corrigió `@package localuser` a `@package local_user` (frankenstyle) y se unificó el estilo de código (indentación de 4 espacios y sintaxis de arrays `[]`).

5. **Pruebas unitarias y documentación:**
   - Se agregó `tests/external/get_roles_test.php` (PHPUnit) cubriendo llamadas autorizadas y no autorizadas, con el mismo respaldo `class_alias` para Moodle 3.11–4.1.
   - Se reemplazó `README` por `README.md` con instrucciones de instalación, uso REST/JSON y créditos del repositorio original.

