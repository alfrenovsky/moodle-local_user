# local_user

Plugin local de Moodle que expone el servicio web `local_user_get_roles` (y el servicio preconfigurado `userservice`) para obtener el listado de roles de Moodle (`id`, `localname`, `shortname`, `description`).

## Compatibilidad

- **Moodle:** 3.11 a 5.3
- **PHP:** 7.3 a 8.4+

## Instalación

1. Copiar el contenido del repositorio en `local/user` dentro de la instalación de Moodle.
2. Acceder a **Administración del sitio > Notificaciones** para completar la instalación o actualización.
3. Habilitar los servicios web en **Administración del sitio > Características avanzadas**.
4. Habilitar el protocolo **REST** en **Administración del sitio > Servidor > Servicios web > Gestionar protocolos**.
5. Generar un token para un usuario con la capacidad `moodle/role:manage` asociado al servicio `userservice`.

## Ejemplo de uso (REST + JSON)

```bash
curl -s "https://TU_MOODLE/webservice/rest/server.php?wstoken=TU_TOKEN&wsfunction=local_user_get_roles&moodlewsrestformat=json"
```

También hay un script de ejemplo en `client/client.php`.

## Créditos

- **Repositorio original:** https://gitlab.siu.edu.ar/guarani/moodle-local_user
- **Autor original:** Leonel Leonardis (2019)
- **Mantenimiento:** Alfrenovsky (2026)

