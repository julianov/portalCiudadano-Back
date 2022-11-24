# Acceder a los servicios

* Laravel se ejecuta en el puerto 8001 con nginx como proxy reverso

* Para acceder a la DB, que actualmente es un postgresql, se puede acceder a través del puerto 5435, las credenciales estan en el archivo `docker compose.yml`

* En caso de que no levante la app tenemos que ejecutar el siguiente comando. Esto para que se haga un reload del servicio, hay que revisarlo de todas formas
    ```bash
    docker compose up -d
    ```
    
* Los contenedores disponibles son los siguientes:
    ```
    backend - Para el backend
    database - Para la base de datos
    webserver - Para la instancia de nginx
    ```

# Interactuar con la consola de Laravel
Para ejecutar comandos de php tenemos que ejecutar el siguiente comando

```bash
docker compose exec backend {comando}
```

En caso de que querramos ejecutar comandos de composer, tenemos que ejecutar el siguiente comando

```bash
docker compose exec backend composer {comando}
```

# Oracle DB driver 

For more info check: 

* https://github.com/yajra/laravel-oci8


# Comandos para iniciar el proyecto

* Para iniciar el proyecto tenemos que ejecutar el siguiente comando

Levanta los contenedores y los deja corriendo en segundo plano
```bash 
docker compose up -d
```

Instalamos las dependencias de composer
```bash 
docker compose exec backend composer install --ignore-platform-req=ext-fileinfo 
```

Instalamos passport para laravel
```bash 
docker compose exec backend composer require laravel/passport --ignore-platform-req=ext-fileinfo 
```

Realizamos la migración de la base de datos
```bash 
docker compose exec backend php artisan migrate
```

Generamos las keys de passport
```bash 
docker compose exec backend php artisan passport:keys
```

Generamos las configuraciones de passport
```bash 
docker compose exec backend php artisan passport:install
```

Levantar servidor
```bash 
docker compose exec backend php artisan serve
```