

## Instalación

Instalar dependencias
```
composer install
```

Cambiar la configuración de la base de datos en el archivo .env
Ejemplo:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar las migraciones
```
php artisan migrate
```

Sembrar la base de datos con los datos iniciales mediante el seeder
```
php artisan db:seed
```

# Testing
Para ejecutar los tests debemos cambiar el nombre a la base de datos en los environments para crear una nueva y asi que los tests no afecten la base de datos que utilizamos normalmente.
Para eso vamos al archivo .env y cambiamos DB_DATABASE
Ejemplo:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=

```

Luego de eso ejecutamos las migraciones

```
php artisan migrate
```

Y ya podemos ejecutar los tests con
```
php artisan test
```

