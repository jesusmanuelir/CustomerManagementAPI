# EasyCustomer API

Este proyecto es un sistema RESTful simple desarrollado en Laravel para registrar clientes, realizar consultas y eliminar registros.

## Requisitos

- Apache >= 2.4.56
- PHP >= 8.1.17
- Composer >= 2.5.7
- MariaDB >= 10.4.28

## Instalación

1. Clonar este repositorio: 
```sh 
git clone https://github.com/jesusmanuelir/CustomerManagementAPI
```
2. Navegar al directorio del proyecto: 
```sh 
cd CustomerManagementAPI
```
3. Instalar todas las dependencias
```sh
composer install
```
4. Copiar el archivo `.env.example` a `.env`: 
```sh 
cp .env.example .env
```
5. Configurar la conexión a la base de datos y otros parámetros en el archivo `.env`
```sh 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={You DB NAME}
DB_USERNAME={You DB USERNAME}
DB_PASSWORD={You DB PASSWORD}
```
6. Ejecutar migraciones y seeders: 
```sh 
php artisan migrate
```
```sh
 php artisan db:seed --class=UsersTableSeeder
``` 
```sh
 php artisan db:seed --class=ChileanTableSeeder
 ```

## Servicios Disponibles

### Autenticación

*URL:* `/api/auth/login`

*Método:* POST

*Parámetros:* email, password.

*Descripción:* Retorna un token SHA1 único si se inicia sesión correctamente, especificando su tiempo de expiración. Utilice este token como Bearer Token en los encabezados Authorization para acceder a los demás servicios protegidos por autenticación.

El token tiene un tiempo de expiración 15 minutos, lo puede cambiar en el archivo `.env` en la variable de entorno `TOKEN_EXPIRATION_MINUTES=15`.

Para ejecutar una prueba puede ver los emails de los usuarios de prueba registrados en la tabla `users`, todos tiene la misma clave `secret`.

### Registrar Clientes 

*URL:* `/api/customers/create`

*Método:* POST

*Parámetros:*
- name (obligatorio)
- last_name (obligatorio)
- dni (obligatorio)
- email (obligatorio)
- address (opcional)
- id_reg (obligatorio)
- id_com (obligatorio)

*Validaciones:*
- El token de autenticación es obligatorio.
- Los campos obligatorios deben estar presentes.
- La comuna y la región deben existir y estar relacionadas.
- La comuna y la región deben tener status `A` (activo).

*Descripción:* Registra nuevos clientes asociándolos con su comuna y región correspondiente.

### Consultar Clientes

*URL:* `/api/customers/search`

*Método:* GET

*Parámetros:* dni o email (al menos uno de los dos es necesario).

*Validaciones:*
- El token de autenticación es obligatorio.
- El cliente debe tener status `A` (activo).
- Al menos uno de los parámetros debe ser proporcionado.

*Descripción:* Consulta un cliente por DNI o correo electrónico. Retorna información básica del cliente, junto con la descripción de su región y comuna.

### Eliminar lógicamente un Cliente

*URL:* `/api/customers/delete/{dni}`

 *Método:* PATCH

 *Parámetro:* dni (en la URL)
 
 *Validaciones:*
 - El token de autenticación es obligatorio.
 - El cliente debe existir y tener status `A` (activo) o `I`(inactivo).

*Descripción:* Elimina lógicamente a un cliente del sistema cambiando su estado a "trash".
## Configuración .env
En el archivo `.env` se realizó una configuración especial:
- `LOG_CHANNEL=request_response` Depende del valor de la variable APP_ENV. Si está en producción (APP_ENV=production), solo registra eventos con nivel "info" o superior; si no está en producción, registra eventos a partir del nivel "debug".
- `TOKEN_EXPIRATION_MINUTES=15` Su función es establecer el tiempo de expiración del token. En este caso son 15 minutos, por ejemplo si desea que sea una hora sería 60. 
- `APP_DEBUG=false` Desactivará el registro detallado en producción, dejando solo registros básicos habilitados.

## Pruebas de la API con Postman

Para probar los servicios disponibles de la API, puedes utilizar [Postman](https://www.postman.com/). Una vez instalado e iniciado Postman, sigue estos pasos:

1. Importa archivo `EasyCustomer API.postman_collection` (ubicado en la carpeta raiz) en tu Postman. Despues de importar debe reemplaza `{host_url}` en todas las peticiones por la dirección que estés utilizando, por ejemplo: http://127.0.0.1:8000.

2. El primer request a ejecutar es `Login`. Para ello, ve a la opción 'Body' y proporciona el correo electrónico del usuario (puedes encontrarlo en la tabla `user`). La contraseña ya está indicada. Tras ejecutar esta petición, recibirás una respuesta que incluye un token para usar en los demás servicios.

*Customer*
Para crear un registro de clientes, selecciona el request llamado `Customer`. Agrega el token obtenido anteriormente en la opción de `Authorization`, específicamente bajo `Bearer Token`. Luego, completa todos los valores requeridos en la sección `Params`.

*Search*
Si deseas buscar clientes por DNI o correo electrónico, selecciona el request denominado `Search`. Agrega nuevamente el token como `Bearer Token` dentro de `Authorization` y rellena los campos necesarios en `Params`.

*Delete*
Para realizar una eliminación lógica del cliente, utiliza el request titulado `Delete`. Al igual que antes, agrega el token correspondiente como `Bearer Token` dentro de `Authorization` y reemplaza {dni} dentro de la URL por el DNI del cliente que deseas eliminar.
## License

MIT
