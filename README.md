# Acceder a los servicios

* Antes de poner en marcha la aplicacion, debemos configurar nuestras variables de entorno

```bash
   .env
```


## Oracle DB driver

For more info check:

* <https://github.com/yajra/laravel-oci8>

## Comandos para iniciar el proyecto

* Para iniciar el proyecto tenemos que ejecutar el siguiente comando


Instalamos las dependencias de composer

```bash
composer install --ignore-platform-req=ext-fileinfo 
```

Instalamos passport para laravel

```bash
composer require laravel/passport --ignore-platform-req=ext-fileinfo 
```

Realizamos la migración de la base de datos, en caso de ser necesario. 

```bash
php artisan migrate
```

Generamos las keys de passport

```bash
php artisan passport:keys
```

Generamos las configuraciones de passport

```bash
php artisan passport:install
```

Levantar servidor

```bash
php artisan serve
```
