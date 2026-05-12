@echo off
echo Configurando proyecto Laravel con SQLite...

:: Crear .env si no existe
if not exist .env (
    copy .env.example .env
    echo Archivo .env creado desde .env.example.
)

:: Asegurar base de datos SQLite
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo Base de datos SQLite creada en database\database.sqlite.
)

:: Instalar dependencias
echo Instalando dependencias de Composer...
call composer install

:: Generar clave de aplicación
echo Generando clave de aplicacion...
php artisan key:generate

:: Migrar y poblar
echo Ejecutando migraciones y seeders (usuarios de prueba)...
php artisan migrate:fresh --seed

echo.
echo ==================================================
echo  Configuracion completada con exito.
echo ==================================================
echo.
echo Usuarios de prueba cargados:
echo - admin@ffe.local
echo - direccion@ffe.local
echo - coordinacion@ffe.local
echo - tutor@ffe.local
echo - profesor@ffe.local
echo - secretaria@ffe.local
echo - empresa@ffe.local
echo.
echo Contraseña para todos: password
echo.
echo Puedes iniciar el servidor con: php artisan serve
pause
