## Requirements 📋

- Docker 🐳
- Git 📚

## Project Installation 🚀

### Clone or download the project ZIP from GitHub:

```
git clone https://github.com/niarfee/shoe-shop-api-laravel-ddd-hexagonal-architecture
```

### Copy `.env.example` and rename it to `.env`:

```
cp .env.example .env
```

### Install dependencies:

```
./docker/composer install
```

### Start Docker environment:

This will start four containers:

- Laravel API Backend
- MySQL database for Laravel (to manage the User, Token, and other tables used
  by Laravel)
- MySQL database for Shop (a decoupled DB with all the necessary domain tables)
- phpMyAdmin (to manage the databases more conveniently locally)

```
./docker/up
```

### Access the backend-laravel Docker container to execute the final installation commands:

```
docker exec -it shoe-shop-api-laravel-ddd-hexagonal-architecture-backend-laravel-1 bash
```

Once inside the container, run the following commands:

#### Generate the application key:

```
php artisan key:generate
```

#### Run migrations and seeders (locally):

```
composer migrate:fresh:seed:local
```

## Run tests (locally):

```
php artisan test
```

## Access the database through phpMyAdmin:

### URL:

```
http://localhost:8080/
```

### Laravel DB credentials (default):

```
server: db-mysql-laravel
user: sail
password: password
```

### Shop DB credentials (default):

```
server: db-mysql-shop
user: sail
password: password
```

## Useful tools for local development:

### PHP CS Fixer:

PHP CS Fixer (PHP Coding Standards Fixer) is a tool that automatically detects
and fixes style and formatting issues in PHP code, following standards such as
PSR-12 or custom rules.

#### Copy `.php-cs-fixer.dist.php` and rename it to `.php-cs-fixer.php`, where all style and formatting rules for the project are defined:

```
cp .php-cs-fixer.dist.php .php-cs-fixer.php
```

#### Run PHP CS Fixer manually:

```
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

#### Run PHP CS Fixer manually (with preview):

```
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff
```

#### Run PHP CS Fixer automatically on file save:

Using the `junstyle.php-cs-fixer` extension for Visual Studio Code, you can
automatically run PHP CS Fixer when saving a file.

1. Install the `junstyle.php-cs-fixer` extension for Visual Studio Code
2. Add the following configuration to `settings.json` in Visual Studio Code:

   ```
   "php-cs-fixer.onsave": true,
   "php-cs-fixer.executablePath": "${workspaceFolder}/vendor/bin/php-cs-fixer",
   "php-cs-fixer.config": ".php-cs-fixer.php;.php-cs-fixer.dist.php",
   "php-cs-fixer.ignorePHPVersion": true,
   ```

### Laravel IDE Helper

#### Install Laravel IDE Helper:

```
composer require --dev barryvdh/laravel-ide-helper
```

#### Generate helper files for the IDE:

This command automatically generates the `_ide_helper.php` and
`_ide_helper_models.php` files to enhance the development experience in your
IDE. Run it manually each time you want to generate the files with the current
project code.

```
composer ide-helper
```
