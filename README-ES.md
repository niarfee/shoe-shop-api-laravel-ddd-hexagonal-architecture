## Requisitos 📋

- Docker 🐳
- Git 📚

## Instalación del proyecto 🚀

### Clonar o descargar el proyecto zip desde GitHub:

```
git clone https://github.com/niarfee/shoe-shop-api-laravel-ddd-hexagonal-architecture
```

### Copia el .env.example y renómbralo a .env:

```
cp .env.example .env
```

### Instalar dependencias:

```
./docker/composer install
```

### Levantar entorno Docker:

Con ello levantaremos cuatro contenedores:

- API Backend Laravel
- Base de datos MySQL para Laravel (para gestionar las tablas User, Token y
  demás tablas con las que trabaja Laravel)
- Base de datos MySQL para Shop (BD desacoplada de Laravel con todas las tablas
  necesarias para mi dominio)
- phpMyAdmin (para gestionar las bases de datos de forma más cómoda en local)

```
./docker/up
```

### Acceder al contenedor backend-laravel de Docker para poder ejecutar los últimos comandos de instalación:

```
docker exec -it shoe-shop-api-laravel-ddd-hexagonal-architecture-backend-laravel-1 bash
```

Una vez estamos dentro del contenedor, ejecutamos los siguientes comandos:

#### Generar la clave de la aplicación:

```
php artisan key:generate
```

#### Ejecutar migraciones y seeders (en local):

```
composer migrate:fresh:seed:local
```

## Ejecutar tests (en local):

```
php artisan test
```

## Acceder a la base de datos a través de phpMyAdmin:

### URL:

```
http://localhost:8080/
```

### Credenciales DB Laravel (por defecto):

```
server: db-mysql-laravel
user: sail
password: password
```

### Credenciales DB Shop (por defecto):

```
server: db-mysql-shop
user: sail
password: password
```

## Complementos útiles para desarrollo en local:

### PHP CS Fixer:

PHP CS Fixer (PHP Coding Standards Fixer) es una herramienta que detecta y
corrige automáticamente problemas de estilo y formato en el código PHP siguiendo
estándares como PSR-12, o reglas personalizadas.

#### Copia el .php-cs-fixer.dist.php y renómbralo a .php-cs-fixer.php, en él se han definido todas las reglas de estilo y formato que se han considerado oportunas para el proyecto:

```
cp .php-cs-fixer.dist.php .php-cs-fixer.php
```

#### Ejecutar manualmente PHP CS Fixer:

```
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

#### Ejecutar manualmente PHP CS Fixer (con preview):

```
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff
```

#### Ejecutar automáticamente PHP CS Fixer al guardar un archivo:

A través de la extensión `junstyle.php-cs-fixer` para Visual Studio Code se
puede ejecutar automáticamente PHP CS Fixer al guardar un archivo.

1. Instalar extensión `junstyle.php-cs-fixer` para Visual Studio Code
2. Añadir al settings.json de Visual Studio Code la siguiente configuración:
   ```
   "php-cs-fixer.onsave": true,
   "php-cs-fixer.executablePath": "${workspaceFolder}/vendor/bin/php-cs-fixer",
   "php-cs-fixer.config": ".php-cs-fixer.php;.php-cs-fixer.dist.php",
   "php-cs-fixer.ignorePHPVersion": true,
   ```

### Laravel ide helper

#### Instalar Laravel ide helper:

```
composer require --dev barryvdh/laravel-ide-helper
```

#### Generar archivos de ayuda para el IDE:

Este comando genera automáticamente los archivos `_ide_helper.php` y
`_ide_helper_models.php` para mejorar la experiencia de desarrollo en el IDE.
Ejecutar manualmente cada vez que se quieran generar los archivos con el código
actual del proyecto.

```
composer ide-helper
```
