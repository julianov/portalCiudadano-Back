
<!doctype html>
<html>
    <head>
        <title>Portal ciudadano - Gobierno de Entre Ríos</title>
        <meta name="description" content="Our first page">
        <meta name="keywords" content="html tutorial template">
        <style>
            #btn_cambiar_pass{
                border:solid 1px green;
                display:inline-block;
                margin-left:auto;
                margin-right:auto;
                text-align:center;
                padding: 5px 5px 5px 5px
            }
            #central{
                display:flex;
                flex-direction: column;
                justify-content: center;
                text-align:center
            }
        </style>
    </head>
    <body>
        <div id="central">
            <h1>{{$user->nombre }} {{$user->apellido}}</h1>
            <h2>PARA CAMBIAR LA CONTRASEÑA PRESIONE EL SIGUIENTE ENLACE</h2>
            
            <a id="btn_cambiar_pass" href="{{'http://127.0.0.1:8001/v0/api/changepasswordvalidation?code=' . $hash ."&cuil=" . $cuil  }}">
                <div >CAMBIAR PASSWORD</div>
            </a>    
        </div>

    </body>
</html>

