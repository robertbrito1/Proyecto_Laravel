<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Proyecto FFE - Gestión de Convenios

Sistema de gestión de convenios para las Fuerzas Fuertes Españolas (FFE).

## 📋 Requisitos

- PHP >= 8.3
- Composer
- Node.js y npm
- Git

## 🚀 Instalación Rápida

```bash
git clone https://github.com/robertbrito1/Proyecto_Laravel.git
cd Proyecto_Laravel
composer run setup
```

## 📝 Instalación Manual

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Configurar .env
cp .env.example .env
php artisan key:generate

# 3. Base de datos
touch database/database.sqlite
php artisan migrate

# 4. Cargar datos iniciales
php artisan db:seed

# 5. Instalar frontend
npm install && npm run build
```

## ▶️ Ejecutar

```bash
php artisan serve
```

Accede a `http://localhost:8000`

## 👥 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@ffe.local | password | Administrador |
| direccion@ffe.local | password | Dirección |
| coordinacion@ffe.local | password | Coordinador FFE |
| tutor@ffe.local | password | Tutor |
| profesor@ffe.local | password | Profesor |
| secretaria@ffe.local | password | Secretaría |
| empresa@ffe.local | password | Empresa |

## 📦 Datos de Base de Datos

Se cargan automáticamente al ejecutar `php artisan db:seed`:
- 2 Departamentos (Informática, Sanidad)
- 7 Usuarios de prueba con roles diferenciados

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 13
- **Frontend**: Blade, Tailwind CSS, Vite
- **Base de Datos**: SQLite

---

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
