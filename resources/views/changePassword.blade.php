
<!doctype html>
<html>
    <head>
        <title>Portal ciudadano - Gobierno de Entre Ríos</title>
        <meta name="description" content="Our first page">
        <meta name="keywords" content="html tutorial template">
    </head>
    <body>
        <div style="textAlign:center">
            <h1>{{$user->nombre }} {{$user->apellido}}</h1>
            <h2>Su código de confirmación es: </h2>
            <h2>{{ $hash }}</h2>
            
            <a href="{{'http://google.com/' . $hash}}">CAMBIAR PASSWORD</a>    
        </div>

    </body>
</html>

