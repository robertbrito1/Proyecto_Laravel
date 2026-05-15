<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

**-- Colaboradores Robert Brito y Bruno Brito**
- **PHP >= 8.3**
- [Composer](https://getcomposer.org/)

** 
1. **Clonar e instalar dependencias**:
   ```bash
   git clone https://github.com/robertbrito1/Proyecto_Laravel.git
   cd Proyecto_Laravel
   composer install
   si no funciona el de arriba coloca este
   composer install --ignore-platform-reqs
   ```

2. **Configurar el entorno**:
   ```bash
   cp .env.example .env
   # El proyecto ya viene pre-configurado para SQLite en el .env
   ```

3. **Preparar la base de datos**:
   ```bash
   
   # Generar clave de aplicación
   php artisan key:generate
   php artisan migrate
   luego de la migracion hay que cargar la base de datos con el siguiente comando
   php artisan db:seed
   ```

---

## 👥 Usuarios de Prueba

Puedes usar las siguientes credenciales para acceder y probar las diferentes funcionalidades del sistema. 
**Contraseña para todos los usuarios:** `password`

| Email | Rol / Permisos |
| :--- | :--- |
| **admin@ffe.local** | Administrador del Sistema |
| **direccion@ffe.local** | Dirección del Centro |
| **coordinacion@ffe.local** | Coordinador FFE |
| **tutor@ffe.local** | Tutor |
| **profesor@ffe.local** | Profesor |
| **secretaria@ffe.local** | Secretaría |
| **empresa@ffe.local** | Empresa Colaboradora |

---

## 🌐 Iniciar el Servidor de Desarrollo

Una vez completada la instalación, inicia el servidor local:
```bash
php artisan serve
```
El servidor estará disponible en: [http://localhost:8000](http://localhost:8000)

---
Flujo de trabajo 

Administrador :
    
    El rol de administrador tiene toda las opciones disponibles y todas las vistas creación de convenios, dar de alta empresas,
    dar de alta usuarios  asignar departamentos  categoría de empresas
Direccion del centro:

    El rol de dirección del centro podrá ver los convenios y podrá firmar los convenios pendientes, solo tendrá dos vistas

Coordinador FFE:

    El rol de coordinador FFE tendrá más vistas podrá ver empresas, gestionar departamentos y dar de alta a empresas, además de crear convenios 

Tutor:

    El rol del tutor podrá ver las empresas contactadas y poder darle estados dependiendo el estado ya sea contactada, pendiente de respuesta y descartadas 
Secretaria:

    Podrá ver las empresas y convenios 
    
Empresa:

     Podra ver sus convenios 


    

    
    



