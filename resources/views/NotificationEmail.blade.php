<!doctype html>
<html lang="es">
    <head>
        <title>Portal ciudadano - Gobierno de Entre Ríos</title>
        <meta name="description" content="Our first page">
        <meta name="keywords" content="html tutorial template">
        <style>
            #btn_cambiar_pass {
                border: solid 1px #00aff0;
                background: #00aff0;
                border-radius: 50px;
                display: inline-block;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
                padding: 0 30px;
                text-decoration: none;
            }
            #central {
                display: flex;
                flex-direction: column;
                justify-content: left;
                text-align: left;
            }
            #btn_validar_email_txt {
                color: white;
                font-size: 1em;
                font-family: sans-serif;
                font-style: normal;
            }
            #info_text {
                font-family: sans-serif;
                font-style: normal;
                font-size: 1.5em;
            }
            #username_text {
                font-family: sans-serif;
                font-style: normal;
                font-size: 2em;
                font-weight: bold;
            }
            #header {
                background-color: #7ca157;
                padding: 30px;
                text-align: left;
            }
            #header h1 {
                margin: 0;
                font-size: 24px;
                color: white;
            }
            .horizontal-line {
                border-top: 1px solid black;
                width: 100%;
            }
        </style>
    </head>
    <body>
    <div id="central">
        <div id="header">
            <h1 style="font-size: 2.3rem; color: var(--maincolor); font-weight: bold; width: 100%; margin-top: 10px; margin-bottom:10px; font-family: serif;">Ciudadano Digital</h1>
       </div>
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="padding: 20px;"> 
                    <h1 id="username_text">{{$title}}</h1>
                        <h2 id="info_text">{{$body }} </h2>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <tr>
                    <td style="padding: 20px;">
                        <img height="64px" width="auto" src="data:image/png;base64,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" alt="Gobierno Entre Ríos"/>
                        <br>
                        <br>
                        <div class="horizontal-line"></div>                    
                        <br>
                        <p style="font-size: 1rem; color: black; width: 100%;">Por cualquier duda o inconveniente, <a href="" style="color: blue;">contáctenos</a></p>
                        <p style="font-size: 1rem; color: black; width: 100%;">Información: <a href="" style="color: blue;">Términos y Condiciones</a></p>
                    </td>    
                </tr>
            </table>
        </div>
    </body>
</html>