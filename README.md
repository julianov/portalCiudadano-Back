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

# IMPLEMENTAR HTTPS

* Una vez obtenidos el certificado y la clave SSL

* Modificar Dockerfiles/nginx/nginx.conf

    server {
        listen 8443 ssl;
        index index.php index.html;
        error_log  /var/log/nginx/error.log;
        access_log /var/log/nginx/access.log;
        root /var/www/public;

        ssl_certificate /etc/letsencrypt/live/jaodevvps.online/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/jaodevvps.online/privkey.pem;

        location ~ \.php$ {
            try_files $uri =404;
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass backend:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param PATH_INFO $fastcgi_path_info;
        }

        location / {
            try_files $uri $uri/ /index.php?$query_string;
            gzip_static on;
        }
    }


* Modificar webserver in docker-compose.yml

        webserver:
                image: nginx:latest
                container_name: webserver
                restart: always
                depends_on:
                    - backend
                ports:
                    - "8443:8443"
                volumes:
                    - ./:/var/www
                    - ./Dockerfiles/nginx:/etc/nginx/conf.d
                    - /etc/letsencrypt:/etc/letsencrypt # Agregando este volumen para usar los certificados SSL/TLS
                networks:
                    app-network:
            
* Luego creamos un middleware 

docker compose exec backend php artisan make:middleware Cors

* App\Http\Middleware\Cors.php

        <?php
        namespace App\Http\Middleware;

        use Closure;
        use Illuminate\Http\Request;

        class Cors
        {
            /**
             * Handle an incoming request.
             *
             * @param  \Illuminate\Http\Request  $request
             * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
             * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
             */
            public function handle(Request $request, Closure $next)
            {
                $response = $next($request);

                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

                return $response;
            }
        }

* Por último en Kernel.php agregamos en protected $middleware al final la linea \App\Http\Middleware\Cors::class, y en protected $routeMiddleware agregamos al final la linea 'cors' => \App\Http\Middleware\Cors::class  
