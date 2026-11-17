# local_user

Plugin local de Moodle (**Roles WS Plugin (SIU)**) desarrollado originalmente para la integración entre **SIU-Guaraní** y **Moodle**.

## Propósito

Moodle no incluye de forma nativa en su API de servicios web una función para listar los roles definidos en la plataforma. Este plugin expone el servicio web `local_user_get_roles` (y el servicio preconfigurado `userservice`) para que **SIU-Guaraní** pueda consultar los roles de Moodle (`id`, `localname`, `shortname`, `description`) y mapear perfiles académicos (como *Docente* o *Alumno*) durante la sincronización de comisiones, cursos y matriculaciones.

## Compatibilidad

- **Moodle:** 3.11 a 5.3
- **PHP:** 7.3 a 8.4+

## Instalación

### Opción 1: Desde un Release en GitHub (recomendado)

1. Descargar el archivo `local_user-v*.zip` desde la sección de [Releases](https://github.com/alfrenovsky/moodle-local_user/releases) *(no descargar `Source code (zip)`, ya que ese archivo no tiene la carpeta raíz `user/` que exige Moodle)*.
2. En Moodle, ir a **Administración del sitio > Extensiones** (o **Plugins**, según la traducción) **> Instalar complemento** (*Site administration > Plugins > Install plugins*).
3. Subir el archivo ZIP descargado y continuar con el asistente para completar la actualización de la base de datos.

### Opción 2: Instalación manual

1. Copiar el contenido del repositorio en `local/user` dentro de la instalación de Moodle.
2. Acceder a **Administración del sitio > Notificaciones** para completar la instalación o actualización.

### Configuración de servicios web

1. Habilitar los servicios web (`enablewebservices`) en **Administración del sitio > Características avanzadas** (`/admin/settings.php?section=optionalsubsystems`).
2. Habilitar el protocolo **REST** en **Administración del sitio > Servidor > Servicios web > Gestionar protocolos** *(en Moodle 3.11: **Extensiones > Servicios web > Gestionar protocolos**)*.
3. Generar un token en **Administración del sitio > Servidor > Servicios web > Gestionar tokens** (`/admin/webservice/tokens.php`) *(en Moodle 3.11: **Extensiones > Servicios web > Gestionar tokens**)* haciendo clic en **Crear token**, eligiendo un usuario con la capacidad `moodle/role:manage` y el servicio `userservice`.

## Ejemplo de uso (REST + JSON)

```bash
curl -s "https://TU_MOODLE/webservice/rest/server.php?wstoken=TU_TOKEN&wsfunction=local_user_get_roles&moodlewsrestformat=json"
```

También hay un script de ejemplo en `client/client.php`.

## Créditos

- **Repositorio original:** https://gitlab.siu.edu.ar/guarani/moodle-local_user
- **Autor original:** Leonel Leonardis (2019)
- **Mantenimiento:** Alfrenovsky (2026)

