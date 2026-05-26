# Laravel 12 Starter AR

Boilerplate para proyectos Laravel 12 con configuración base lista para usar en Argentina.

## Incluye

- Laravel 12
- PHP 8.2+
- Laravel Breeze (autenticación completa: login, registro, reset de password)
- Spatie Laravel Permission (roles y permisos)
- Idioma español (es_AR)
- Timezone: America/Argentina/Buenos_Aires
- MySQL configurado

---

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL
- Node.js y npm

---

## Iniciar un proyecto nuevo desde este boilerplate

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/laravel-12_starter-kit_ar.git nombre-proyecto
cd nombre-proyecto
```

### 2. Instalar dependencias PHP

```bash
# Si usás XAMPP con PHP 8.2:
C:\xampp\php\php.exe C:\xampp\php\composer.phar install

# Si tenés composer y php configurados globalmente:
composer install
```

### 3. Instalar dependencias JS

```bash
npm install
```

### 4. Configurar el entorno

```bash
cp .env.example .env
```

Editá el `.env` con los datos de tu proyecto:

```
APP_NAME="Nombre del Proyecto"
<!-- APP_URL=http://localhost/nombre-proyecto/public -->

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_de_datos
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar la clave de la aplicación

```bash
# XAMPP:
C:\xampp\php\php.exe artisan key:generate

# Global:
php artisan key:generate
```

### 6. Crear la base de datos

Entrá a phpMyAdmin y creá una base de datos nueva con:
- Nombre: el mismo que pusiste en `DB_DATABASE`
- Collation: `utf8mb4_unicode_ci`

### 7. Correr las migraciones

```bash
# XAMPP:
php82 artisan migrate

# Global:
php artisan migrate
```

### 8. Crear el storage link

```bash
# XAMPP:
php82 artisan storage:link

# Global:
php artisan storage:link
```

### 9. Compilar assets

```bash
npm run dev      # modo desarrollo
npm run build    # modo producción
```

### 10. Levantar el servidor local

```bash
# XAMPP: accedé directamente desde el navegador:
http://localhost/nombre-proyecto/public

# O con el servidor de artisan:
php82 artisan serve
```

---

## Comandos de referencia (XAMPP con PHP 8.2)

| Acción | Comando |
|---|---|
| Correr migraciones | `php82 artisan migrate` |
| Rehacer migraciones | `php82 artisan migrate:fresh` |
| Rehacer + seeders | `php82 artisan migrate:fresh --seed` |
| Limpiar caché config | `php82 artisan config:clear` |
| Limpiar caché rutas | `php82 artisan route:clear` |
| Limpiar todo | `php82 artisan optimize:clear` |
| Instalar paquete | `C:\xampp\php\php.exe C:\xampp\php\composer.phar require paquete/nombre` |
| Crear modelo + migración | `php82 artisan make:model NombreModelo -m` |
| Crear controlador | `php82 artisan make:controller NombreController` |
| Crear seeder | `php82 artisan make:seeder NombreSeeder` |
| Ver rutas | `php82 artisan route:list` |

---

## Estructura de roles (Spatie)

Los roles se crean desde un seeder. Para agregar roles y permisos al proyecto,
editá `database/seeders/DatabaseSeeder.php` o creá un seeder específico:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Role::create(['name' => 'admin']);
Role::create(['name' => 'artista']);
Role::create(['name' => 'visitante']);
```

Correr seeders:
```bash
php82 artisan db:seed
```

---

## Notas

- Nunca subas el archivo `.env` al repositorio.
- La carpeta `vendor/` y `node_modules/` no se versionan, se regeneran con `composer install` y `npm install`.
- Para producción usá `npm run build` y `php artisan optimize`.
