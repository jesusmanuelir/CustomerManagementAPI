# EasyCustomer API

Este proyecto es un sistema RESTful simple desarrollado en Laravel para registrar clientes, realizar consultas y eliminar registros de manera suave.

## Requisitos

- PHP >= 8.1.17
- Composer
- Base de datos MySQL o compatible (ver configuración `.env`)

## Instalación

1. Clonar este repositorio: `git clone https://github.com/jesusmanuelir/CustomerManagementAPI`
2. Navegar al directorio del proyecto: `cd CustomerManagementAPI`
3. Ejecutar `composer install` para instalar todas las dependencias
4. Copiar el archivo `.env.example` a `.env`: `cp .env.example .env`
5. Configurar la conexión a la base de datos y otros parámetros en el archivo `.env`
6. En el archivo `.env` establecer el canal para los log y el tiempo de expiración del token de login  
`LOG_CHANNEL=request_response`
`TOKEN_EXPIRATION_MINUTES=15` En este caso son 15 minutos, por ejemplo si desea que sea una hora sería 60.
`APP_DEBUG=false` Esto desactivará el registro detallado en producción, dejando solo registros básicos habilitados.
7. Ejecutar migraciones y seeders: 
 `php artisan migrate`
 `php artisan db:seed --class=UsersTableSeeder` 
 `php artisan db:seed --class=ChileanTableSeeder`

## Servicios Disponibles

### Autenticación

**URL:** `/api/auth/login`

**Método:** POST

**Parámetros:** email, password.

**Descripción:** Retorna un token SHA1 único si se inicia sesión correctamente, especificando su tiempo de expiración. Utilice este token como Bearer Token en los encabezados Authorization para acceder a los demás servicios protegidos por autenticación.

### Registrar Clientes 

**URL:** `/api/customers/create`

**Método:** POST

**Parámetros:**
- name (obligatorio)
- last_name (obligatorio)
- dni (obligatorio)
- email (opcional)
- address (opcional)
- region_id (obligatorio)
- commune_id (obligatorio)

**Validaciones:**
- Los campos obligatorios deben estar presentes.
- La comuna y la región deben existir y estar relacionadas.

**Descripción:** Registra nuevos clientes asociándolos con su comuna y región correspondiente.

### Consultar Clientes

**URL:** `/api/customers/search`

**Método:** GET

**Parámetros:** dni o email (al menos uno de los dos es necesario).

**Validaciones:**
- Al menos uno de los parámetros debe ser proporcionado.

**Descripción:** Consulta un cliente por DNI o correo electrónico. Retorna información básica del cliente, junto con la descripción de su región y comuna.

### Eliminar lógicamente un Cliente

**URL:** `/api/customers/delete/{dni}`

 **Método:** PATCH

 **Parámetro:** dni (en la URL)
 
 **Validaciones:**
 
 - El cliente debe existir y no tener estado "trash".

**Descripción:** Elimina lógicamente a un cliente del sistema cambiando su estado a "trash".
