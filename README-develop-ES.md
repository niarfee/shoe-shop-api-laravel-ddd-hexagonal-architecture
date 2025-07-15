## Creación del proyecto 🌱

### Crear esqueleto de Laravel Sail con docker:

Con el siguiente comando se creará el esqueleto de Laravel con el fichero
docker-compose.yml en el directorio actual:

```
curl -s "https://laravel.build/shoe-shop-api-laravel-ddd-hexagonal-architecture?with=mysql&php=84" | bash
```

### Instalar symfony/uid:

```
composer require symfony/uid
```

### Instalar barryvdh/laravel-ide-helper:

```
composer require --dev barryvdh/laravel-ide-helper
```

### Instalar friendsofphp/php-cs-fixer:

```
composer require --dev friendsofphp/php-cs-fixer
```

### Instalar laravel/sanctum:

```
composer require laravel/sanctum
```
