<!doctype html>
<html>
    <head>
        <title>Portal ciudadano - Gobierno de Entre Ríos</title>
        <meta name="description" content="Our first page">
        <meta name="keywords" content="html tutorial template">
        <style>
            #btn_validar_email{
                border:solid 1px #799f4f;
                background:#799f4f;
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
            #btn_validar_email_txt{
                color:blue;
                font-size:1.5em;
            }
       
        </style>
    </head>
    <body>
    <div id="central">
            <h1>{{$user->nombre }} {{$user->apellido}}</h1>
            <h2>PARA DARSE DE ALTA SIGA EL SIGUIENTE ENLACE</h2>
            
            <a id="btn_validar_email" href="{{'http://127.0.0.1:8001/v0/api/emailvalidation?code=' . $hash ."&cuil=" . $cuil  }}">
                <p id="btn_validar_email_txt">VERIFICAR EMAIL</p>
            </a>    
        </div>
    </body>
</html>