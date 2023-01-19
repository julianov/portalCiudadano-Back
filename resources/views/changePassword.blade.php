<!doctype html>
<html lang="es">
    <head>
        <title>Portal ciudadano - Gobierno de Entre Ríos</title>
        <meta name="description" content="Our first page">
        <meta name="keywords" content="html tutorial template">
         <style>
            #btn_validar_email{
                border:solid 1px #799f4f;
                background:#799f4f;
                border-radius: 50px;
                display:inline-block;
                margin-left:auto;
                margin-right:auto;
                text-align:center;
                padding: 0 30px;
                text-decoration: none;
            }
            #central{
                display:flex;
                flex-direction: column;
                justify-content: center;
                text-align:center
            }
            #btn_validar_email_txt{
                color:white;
                font-size:1em;
                font-family: sans-serif;
                font-style: normal;
            }
            #info_text{
                font-family: sans-serif;
                font-style: normal;
                font-size:1.5em;
            }
            #username_text{
                font-family: sans-serif;
                font-style: normal;
                font-size:2em;
                font-weight: bold;
            }
       
        </style>
    </head>
    <body>
        <div id="central">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="padding: 20px; background-color: #333; color: #fff; font-size: 36px; text-align: center;">
                        <img height="128px" width="auto" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDIwMCA4MCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAwIDgwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojNzk5RjRGO30NCjwvc3R5bGU+DQo8cGF0aCBjbGFzcz0ic3QwIiBkPSJNOC4yLDI5LjdjLTIuMS0xLjItMy43LTIuOC00LjktNC45Yy0xLjItMi4xLTEuOC00LjUtMS44LTcuMWMwLTIuNywwLjYtNSwxLjgtNy4xczIuOC0zLjcsNC45LTQuOQ0KCWMyLjEtMS4yLDQuNS0xLjgsNy4yLTEuOGMzLjksMCw3LjIsMS40LDkuOSw0LjFsLTIuOSwyLjdjLTEuOC0xLjgtNC4xLTIuOC02LjgtMi44Yy0xLjksMC0zLjUsMC40LTUsMS4yYy0xLjQsMC44LTIuNiwyLTMuNCwzLjQNCgljLTAuOCwxLjUtMS4yLDMuMi0xLjIsNXMwLjQsMy42LDEuMiw1YzAuOCwxLjUsMS45LDIuNiwzLjQsMy40YzEuNCwwLjgsMy4xLDEuMiw1LDEuMmMxLjMsMCwyLjYtMC4yLDMuOC0wLjdzMi4zLTEuMiwzLjItMi4xbDIuOCwzDQoJYy0yLjcsMi42LTYsMy45LTEwLDMuOUMxMi43LDMxLjQsMTAuMywzMC44LDguMiwyOS43eiBNMjkuMiw0LjljMC41LTAuNSwxLjEtMC43LDEuOS0wLjdjMC44LDAsMS41LDAuMiwyLDAuNw0KCWMwLjUsMC41LDAuOCwxLjEsMC44LDEuOWMwLDAuNy0wLjMsMS4zLTAuOCwxLjhjLTAuNSwwLjUtMS4yLDAuNy0yLDAuN2MtMC44LDAtMS40LTAuMi0xLjktMC43Yy0wLjUtMC41LTAuOC0xLjEtMC44LTEuOA0KCUMyOC40LDYuMSwyOC43LDUuNCwyOS4yLDQuOXogTTI5LDEyLjJoNC4ydjE4LjdIMjlWMTIuMnogTTQxLjksMzAuMmMtMS4yLTAuNy0yLjEtMS43LTIuNy0zcy0xLTIuOC0xLTQuNlYxMi4yaDQuMnYxMC4xDQoJYzAsMS42LDAuNCwyLjksMS4yLDMuOGMwLjgsMC45LDEuOSwxLjQsMy4yLDEuNGMxLjUsMCwyLjgtMC41LDMuNy0xLjVjMS0xLDEuNC0yLjMsMS40LTMuOXYtOS44aDQuMnYxOC43aC0zLjhsMC0yLjQNCgljLTEuNiwxLjktMy43LDIuOC02LjQsMi44QzQ0LjQsMzEuMyw0My4xLDMwLjksNDEuOSwzMC4yeiBNNjQuNiwzMC4xYy0xLjQtMC44LTIuNS0xLjktMy4zLTMuNGMtMC44LTEuNS0xLjItMy4yLTEuMi01LjENCgljMC0xLjksMC40LTMuNiwxLjItNS4xYzAuOC0xLjUsMS45LTIuNiwzLjMtMy40YzEuNC0wLjgsMy0xLjIsNC43LTEuMmMxLjMsMCwyLjYsMC4yLDMuNywwLjZjMS4xLDAuNCwyLDEsMi44LDEuOFY0LjRIODB2MjYuNWgtMy44DQoJbDAtMi41Yy0wLjcsMC45LTEuNywxLjYtMi45LDIuMWMtMS4yLDAuNS0yLjUsMC44LTQsMC44QzY3LjYsMzEuMyw2NiwzMC45LDY0LjYsMzAuMXogTTY2LDI1LjhjMS4xLDEuMSwyLjQsMS42LDQuMSwxLjYNCgljMS43LDAsMy4xLTAuNSw0LjEtMS42YzEuMS0xLjEsMS42LTIuNSwxLjYtNC4zYzAtMS43LTAuNS0zLjItMS42LTQuM2MtMS4xLTEuMS0yLjQtMS42LTQuMS0xLjZjLTEuNywwLTMuMSwwLjUtNC4xLDEuNg0KCWMtMS4xLDEuMS0xLjYsMi41LTEuNiw0LjNDNjQuNCwyMy4zLDY0LjksMjQuNyw2NiwyNS44eiBNODguNCwzMC4xYy0xLjQtMC44LTIuNS0yLTMuMy0zLjVjLTAuOC0xLjUtMS4yLTMuMi0xLjItNS4xDQoJYzAtMS45LDAuNC0zLjYsMS4yLTUuMWMwLjgtMS41LDEuOS0yLjYsMy4zLTMuNWMxLjQtMC44LDMtMS4yLDQuOC0xLjJjMS40LDAsMi43LDAuMywzLjksMC44YzEuMiwwLjUsMi4xLDEuMiwyLjksMi4xbDAtMi41aDMuOA0KCXYxOC43aC0zLjhsMC0yLjVjLTAuNywwLjktMS43LDEuNi0yLjksMi4xYy0xLjIsMC41LTIuNSwwLjgtMy45LDAuOEM5MS40LDMxLjMsODkuOCwzMC45LDg4LjQsMzAuMXogTTg5LjgsMjUuOA0KCWMxLjEsMS4xLDIuNCwxLjYsNC4xLDEuNmMxLjcsMCwzLjEtMC41LDQuMS0xLjZjMS4xLTEuMSwxLjYtMi41LDEuNi00LjNjMC0xLjctMC41LTMuMi0xLjYtNC4zYy0xLjEtMS4xLTIuNC0xLjYtNC4xLTEuNg0KCWMtMS43LDAtMy4xLDAuNS00LjEsMS42Yy0xLjEsMS4xLTEuNiwyLjUtMS42LDQuM0M4OC4yLDIzLjMsODguNywyNC43LDg5LjgsMjUuOHogTTExMi4zLDMwLjFjLTEuNC0wLjgtMi41LTEuOS0zLjMtMy40DQoJYy0wLjgtMS41LTEuMi0zLjItMS4yLTUuMWMwLTEuOSwwLjQtMy42LDEuMi01LjFjMC44LTEuNSwxLjktMi42LDMuMy0zLjRjMS40LTAuOCwzLTEuMiw0LjctMS4yYzEuMywwLDIuNiwwLjIsMy43LDAuNnMyLDEsMi44LDEuOA0KCVY0LjRoNC4ydjI2LjVoLTMuOGwwLTIuNWMtMC43LDAuOS0xLjcsMS42LTIuOSwyLjFjLTEuMiwwLjUtMi41LDAuOC00LDAuOEMxMTUuMiwzMS4zLDExMy42LDMwLjksMTEyLjMsMzAuMXogTTExMy42LDI1LjgNCgljMS4xLDEuMSwyLjQsMS42LDQuMSwxLjZzMy4xLTAuNSw0LjEtMS42YzEuMS0xLjEsMS42LTIuNSwxLjYtNC4zYzAtMS43LTAuNS0zLjItMS42LTQuM2MtMS4xLTEuMS0yLjQtMS42LTQuMS0xLjYNCglzLTMuMSwwLjUtNC4xLDEuNmMtMS4xLDEuMS0xLjYsMi41LTEuNiw0LjNDMTEyLDIzLjMsMTEyLjYsMjQuNywxMTMuNiwyNS44eiBNMTM2LjEsMzAuMWMtMS40LTAuOC0yLjUtMi0zLjMtMy41DQoJYy0wLjgtMS41LTEuMi0zLjItMS4yLTUuMWMwLTEuOSwwLjQtMy42LDEuMi01LjFjMC44LTEuNSwxLjktMi42LDMuMy0zLjVjMS40LTAuOCwzLTEuMiw0LjgtMS4yYzEuNCwwLDIuNywwLjMsMy45LDAuOA0KCWMxLjIsMC41LDIuMSwxLjIsMi45LDIuMWwwLTIuNWgzLjh2MTguN2gtMy44bDAtMi41Yy0wLjcsMC45LTEuNywxLjYtMi45LDIuMWMtMS4yLDAuNS0yLjUsMC44LTMuOSwwLjgNCglDMTM5LjEsMzEuMywxMzcuNSwzMC45LDEzNi4xLDMwLjF6IE0xMzcuNCwyNS44YzEuMSwxLjEsMi40LDEuNiw0LjEsMS42czMuMS0wLjUsNC4xLTEuNmMxLjEtMS4xLDEuNi0yLjUsMS42LTQuMw0KCWMwLTEuNy0wLjUtMy4yLTEuNi00LjNjLTEuMS0xLjEtMi40LTEuNi00LjEtMS42cy0zLjEsMC41LTQuMSwxLjZjLTEuMSwxLjEtMS42LDIuNS0xLjYsNC4zQzEzNS45LDIzLjMsMTM2LjQsMjQuNywxMzcuNCwyNS44eg0KCSBNMTU2LjcsMTIuMmgzLjhsMCwyLjVjMS42LTEuOSwzLjctMi44LDYuNS0yLjhjMS42LDAsMywwLjQsNC4yLDEuMXMyLjEsMS43LDIuOCwzYzAuNywxLjMsMSwyLjksMSw0LjZ2MTAuM2gtNC4yVjIwLjgNCgljMC0xLjYtMC40LTIuOS0xLjItMy44Yy0wLjgtMC45LTEuOS0xLjQtMy4zLTEuNGMtMS41LDAtMi44LDAuNS0zLjgsMS41cy0xLjUsMi4zLTEuNSwzLjl2OS44aC00LjJWMTIuMnogTTE4My4zLDMwLjENCgljLTEuNS0wLjgtMi43LTItMy41LTMuNXMtMS4yLTMuMS0xLjItNWMwLTEuOSwwLjQtMy41LDEuMi01czItMi42LDMuNS0zLjVjMS41LTAuOCwzLjMtMS4zLDUuMy0xLjNjMiwwLDMuNywwLjQsNS4yLDEuMw0KCWMxLjUsMC44LDIuNywyLDMuNSwzLjVzMS4yLDMuMSwxLjIsNWMwLDEuOS0wLjQsMy41LTEuMiw1cy0yLDIuNi0zLjUsMy41Yy0xLjUsMC44LTMuMywxLjMtNS4yLDEuMw0KCUMxODYuNSwzMS4zLDE4NC44LDMwLjksMTgzLjMsMzAuMXogTTE4My41LDI0LjVjMC41LDAuOSwxLjIsMS42LDIsMi4xYzAuOSwwLjUsMS45LDAuOCwzLDAuOHMyLjEtMC4zLDMtMC44YzAuOS0wLjUsMS41LTEuMiwyLTIuMQ0KCWMwLjUtMC45LDAuNy0xLjksMC43LTNjMC0xLjEtMC4yLTIuMS0wLjctM2MtMC41LTAuOS0xLjEtMS42LTItMi4xYy0wLjktMC41LTEuOS0wLjgtMy0wLjhzLTIuMSwwLjMtMywwLjhjLTAuOSwwLjUtMS41LDEuMi0yLDIuMQ0KCWMtMC41LDAuOS0wLjcsMS45LTAuNywzQzE4Mi44LDIyLjYsMTgzLDIzLjYsMTgzLjUsMjQuNXogTTQzLjYsMzguNGgxMC4xYzIuNiwwLDQuOSwwLjUsNywxLjZjMiwxLjEsMy42LDIuNiw0LjcsNC43DQoJYzEuMSwyLDEuNyw0LjMsMS43LDYuOWMwLDIuNi0wLjYsNC45LTEuNyw2LjljLTEuMSwyLTIuNywzLjYtNC43LDQuN2MtMiwxLjEtNC40LDEuNi03LDEuNkg0My42VjM4LjR6IE01My43LDYwLjgNCgljMS43LDAsMy4zLTAuNCw0LjYtMS4yYzEuMy0wLjgsMi40LTEuOCwzLjEtMy4yYzAuNy0xLjQsMS4xLTMsMS4xLTQuOGMwLTEuOC0wLjQtMy40LTEuMS00LjhjLTAuNy0xLjQtMS44LTIuNS0zLjEtMy4yDQoJYy0xLjMtMC44LTIuOS0xLjItNC42LTEuMkg0OHYxOC4zSDUzLjd6IE03MS41LDM4LjljMC41LTAuNSwxLjEtMC43LDEuOS0wLjdjMC44LDAsMS41LDAuMiwyLDAuN2MwLjUsMC41LDAuOCwxLjEsMC44LDEuOQ0KCWMwLDAuNy0wLjMsMS4zLTAuOCwxLjhjLTAuNSwwLjUtMS4yLDAuNy0yLDAuN2MtMC44LDAtMS40LTAuMi0xLjktMC43Yy0wLjUtMC41LTAuOC0xLjEtMC44LTEuOEM3MC43LDQwLjEsNzEsMzkuNCw3MS41LDM4Ljl6DQoJIE03MS4zLDQ2LjJoNC4ydjE4LjdoLTQuMlY0Ni4yeiBNODQuMiw3NS4xYy0xLjUtMC43LTIuNy0xLjctMy41LTNjLTAuOC0xLjMtMS4yLTIuNy0xLjEtNC40aDQuMmMtMC4xLDEuNCwwLjQsMi41LDEuNSwzLjMNCgljMSwwLjgsMi41LDEuMiw0LjIsMS4yYzEuNywwLDMuMS0wLjQsNC4xLTEuMWMxLTAuNywxLjYtMS43LDEuNi0yLjljMC0xLjItMC41LTIuMS0xLjUtMi44Yy0xLTAuNy0yLjQtMS00LjEtMQ0KCWMtMiwwLTMuOC0wLjQtNS40LTEuMWMtMS41LTAuOC0yLjctMS44LTMuNS0zLjNjLTAuOC0xLjQtMS4yLTMuMS0xLjItNC45YzAtMS44LDAuNC0zLjQsMS4zLTQuOGMwLjktMS40LDItMi42LDMuNi0zLjQNCgljMS41LTAuOCwzLjItMS4yLDUuMS0xLjJjMiwwLDMuNywwLjQsNS4xLDEuMmwyLjQtMi45bDMuMSwyLjNsLTIuNiwzYzAuOCwwLjksMS4zLDEuOCwxLjcsMi43YzAuNCwwLjksMC41LDEuOSwwLjUsMw0KCWMwLDEuNy0wLjQsMy4xLTEuMiw0LjNjLTAuOCwxLjItMiwyLjItMy42LDIuOWMxLjUsMC42LDIuNiwxLjMsMy40LDIuM2MwLjgsMSwxLjIsMi4yLDEuMiwzLjZjMCwxLjYtMC40LDIuOS0xLjIsNC4xDQoJcy0yLDIuMS0zLjUsMi43Yy0xLjUsMC42LTMuMiwwLjktNS4yLDAuOUM4Ny41LDc2LjEsODUuNyw3NS44LDg0LjIsNzUuMXogTTg1LjMsNTkuMmMxLjEsMSwyLjUsMS41LDQuMiwxLjVzMy4xLTAuNSw0LjItMS41DQoJYzEuMS0xLDEuNi0yLjMsMS42LTRjMC0xLjctMC41LTMtMS42LTRzLTIuNS0xLjUtNC4yLTEuNWMtMS44LDAtMy4yLDAuNS00LjIsMS41cy0xLjYsMi40LTEuNiw0QzgzLjcsNTYuOSw4NC4zLDU4LjIsODUuMyw1OS4yeg0KCSBNMTA0LDM4LjljMC41LTAuNSwxLjEtMC43LDEuOS0wLjdjMC44LDAsMS41LDAuMiwyLDAuN2MwLjUsMC41LDAuOCwxLjEsMC44LDEuOWMwLDAuNy0wLjMsMS4zLTAuOCwxLjhjLTAuNSwwLjUtMS4yLDAuNy0yLDAuNw0KCWMtMC44LDAtMS40LTAuMi0xLjktMC43Yy0wLjUtMC41LTAuOC0xLjEtMC44LTEuOEMxMDMuMiw0MC4xLDEwMy41LDM5LjQsMTA0LDM4Ljl6IE0xMDMuOCw0Ni4yaDQuMnYxOC43aC00LjJWNDYuMnogTTExNi44LDYzLjUNCgljLTEuMS0xLjEtMS42LTIuOC0xLjYtNXYtOC43aC0zLjV2LTMuNmgzLjV2LTQuOWw0LjItMC41djUuM2g1LjR2My42aC01LjR2OC43YzAsMSwwLjIsMS43LDAuNywyLjJjMC40LDAuNSwxLjEsMC44LDEuOSwwLjgNCgljMC44LDAsMS41LTAuMiwyLjMtMC42bDEuMiwzLjRjLTAuNywwLjMtMS40LDAuNi0yLjEsMC43Yy0wLjYsMC4xLTEuNCwwLjItMi4xLDAuMkMxMTkuNCw2NS4yLDExNy45LDY0LjYsMTE2LjgsNjMuNXogTTEzMiw2NC4xDQoJYy0xLjQtMC44LTIuNS0yLTMuMy0zLjVjLTAuOC0xLjUtMS4yLTMuMi0xLjItNS4xYzAtMS45LDAuNC0zLjYsMS4yLTUuMWMwLjgtMS41LDEuOS0yLjYsMy4zLTMuNWMxLjQtMC44LDMtMS4yLDQuOC0xLjINCgljMS40LDAsMi43LDAuMywzLjksMC44YzEuMiwwLjUsMi4xLDEuMiwyLjksMi4xbDAtMi41aDMuOHYxOC43aC0zLjhsMC0yLjVjLTAuNywwLjktMS43LDEuNi0yLjksMi4xYy0xLjIsMC41LTIuNSwwLjgtMy45LDAuOA0KCUMxMzUsNjUuMywxMzMuNCw2NC45LDEzMiw2NC4xeiBNMTMzLjMsNTkuOGMxLjEsMS4xLDIuNCwxLjYsNC4xLDEuNnMzLjEtMC41LDQuMS0xLjZjMS4xLTEuMSwxLjYtMi41LDEuNi00LjMNCgljMC0xLjctMC41LTMuMi0xLjYtNC4zYy0xLjEtMS4xLTIuNC0xLjYtNC4xLTEuNnMtMy4xLDAuNS00LjEsMS42Yy0xLjEsMS4xLTEuNiwyLjUtMS42LDQuM0MxMzEuOCw1Ny4zLDEzMi4zLDU4LjcsMTMzLjMsNTkuOHoNCgkgTTE1Mi42LDM4LjRoNC4ydjI2LjVoLTQuMlYzOC40eiIvPg0KPC9zdmc+DQo=" alt="Ciudadano Digital"/>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px;"> 
                        <h1>{{$user->name }} {{$user->last_name}}</h1>
                        <h2>PARA CAMBIAR LA CONTRASEÑA PRESIONE EL SIGUIENTE ENLACE</h2>
                        <br>
                        <a id="btn_cambiar_pass" href="{{'https://ciudadano-digital-er.web.app/reiniciarcontraseña?code=' . $hash ."&cuil=" . $cuil  }}">
                            <p id="btn_validar_email_txt">CAMBIAR PASSWORD</p>
                        </a> 
                        <br>
                        <br>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px; background-color: #333; color: #fff; text-align: center;">
                        <img height="128px" width="auto" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDkzLjMgMjQuNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgOTMuMyAyNC40OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojNzk5RjRGO30NCjwvc3R5bGU+DQo8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzIuNCwxNC44YzAuMiwwLjksMC45LDEuNiwyLjMsMS42YzAuNiwwLDEuNi0wLjIsMi0wLjZsMS4xLDEuMWMtMC44LDAuOS0yLDEuMi0zLjEsMS4yDQoJYy0yLjcsMC00LjItMS42LTQuMi0zLjlzMS42LTQuMSw0LjEtNC4xYzIuNSwwLDQuMSwxLjYsMy43LDQuN0gzMi40eiBNMzYuNiwxMy4zTDM2LjYsMTMuM2MtMC4yLTAuOS0wLjktMS40LTItMS40DQoJYy0wLjksMC0xLjksMC41LTIsMS40SDM2LjZ6IE00NSwxOHYtMy45YzAtMS4yLTAuNi0yLTEuOS0yYy0xLjEsMC0xLjksMC45LTEuOSwyVjE4aC0xLjl2LTcuNWgxLjdsMC4yLDAuOWMwLjgtMC44LDEuNC0xLjEsMi41LTEuMQ0KCWMxLjcsMCwzLjEsMS40LDMuMSwzLjdWMThINDV6IE01MC41LDguM3YyLjJoMi4yVjEyaC0yLjJ2My4zYzAsMC44LDAuNSwxLjEsMC45LDEuMWMwLjMsMCwwLjYtMC4yLDAuOS0wLjNsMC42LDEuNg0KCWMtMC42LDAuMy0xLjEsMC4zLTEuNiwwLjVjLTEuNywwLTIuOC0wLjktMi44LTIuOFYxMmgtMS40di0xLjZoMS40di0yTDUwLjUsOC4zeiBNNTUuNSwxMC41bDAuMiwwLjhjMC41LTAuOSwxLjItMC45LDItMC45DQoJYzAuOCwwLDEuNiwwLjIsMiwwLjZsLTAuOSwxLjZjLTAuMy0wLjMtMC42LTAuNS0xLjItMC41Yy0xLjEsMC0xLjksMC42LTEuOSwyVjE4aC0xLjl2LTcuNUg1NS41eiBNNjEuNywxNC44YzAsMC45LDAuOSwxLjYsMi4yLDEuNg0KCWMwLjYsMCwxLjYtMC4yLDItMC42bDEuMiwxLjFjLTAuOSwwLjktMi4yLDEuMi0zLjMsMS4yYy0yLjcsMC00LjItMS42LTQuMi0zLjlzMS42LTQuMSw0LjEtNC4xYzIuNSwwLDQuMSwxLjYsMy43LDQuN0g2MS43eg0KCSBNNjUuOCwxMy4zTDY1LjgsMTMuM2MtMC4yLTAuOS0wLjktMS40LTItMS40Yy0wLjksMC0xLjcsMC41LTIsMS40SDY1Ljh6IE03MC4zLDEwLjVsMC4yLDAuOGMwLjYtMC45LDEuNC0wLjksMi4yLTAuOQ0KCXMxLjQsMC4yLDEuOSwwLjZsLTAuOCwxLjZjLTAuNS0wLjMtMC44LTAuNS0xLjQtMC41Yy0wLjksMC0xLjksMC42LTEuOSwyVjE4aC0xLjl2LTcuNUg3MC4zeiBNNzUsMTh2LTcuM2gxLjlWMThINzV6IE03Ny42LDcuMw0KCUw3Ny42LDcuM2gtMkw3NSw5LjVsMCwwaDEuNkw3Ny42LDcuM0w3Ny42LDcuM3ogTTg1LjksMTQuMmMwLDIuMi0xLjQsMy45LTMuOSwzLjljLTIuMywwLTMuOS0xLjctMy45LTMuOXMxLjYtMy45LDMuOS0zLjkNCglDODQuNSwxMC4zLDg1LjksMTIsODUuOSwxNC4yeiBNODAsMTQuMkw4MCwxNC4yYzAsMS4xLDAuOCwyLjIsMiwyLjJjMS40LDAsMi0xLjEsMi0yLjJTODMuMywxMiw4MiwxMlM4MCwxMy4xLDgwLDE0LjJ6IE05MS44LDEyLjUNCgljLTAuNS0wLjYtMS4xLTAuOC0xLjktMC44cy0xLjIsMC4zLTEuMiwwLjhjMCwwLjUsMC41LDAuOCwxLjQsMC44YzEuNCwwLjIsMy4xLDAuNSwzLjEsMi41YzAsMS4yLTEuMSwyLjUtMy4xLDIuNQ0KCWMtMS4yLDAtMi41LTAuMy0zLjYtMS40bDAuOS0xLjRjMC42LDAuNiwxLjcsMS4xLDIuNywxLjFjMC42LDAsMS4yLTAuMywxLjItMC45YzAtMC41LTAuMy0wLjYtMS40LTAuOGMtMS40LDAtMy4xLTAuNi0zLjEtMi4zDQoJczEuOS0yLjMsMy4xLTIuM3MyLDAuMywzLDEuMUw5MS44LDEyLjV6IE0yNi40LDMuMWMtMy4zLDAtNi4yLDEuNC04LjEsMy43Yy0xLjEsMS4yLTEuNywyLjgtMi4yLDQuN2MtMC4yLDAuNiwwLDIuMiwwLDIuMnYwLjkNCgljMCwwLTAuOCwyLTEuNiwyLjhjLTEuNiwxLjYtMy43LDItNS42LDEuNGwwLDBsNi40LTYuNGMwLjMtMi41LDEuMi00LjcsMi44LTYuNmMtMC42LTAuNS0xLjItMS4xLTItMS40QzE0LjYsMy40LDEyLjUsMywxMC43LDMNCglDOCwzLDUuMiwzLjksMy4yLDYuMUMxLjMsNy44LDAuNCwxMC4zLDAsMTIuNmMwLDAuMywwLDAuNiwwLDAuOWMwLDEuOSwwLjUsMy43LDEuNiw1LjVjMC41LDAuOCwwLjksMS40LDEuNiwyczEuMiwxLjEsMiwxLjYNCgljMy4zLDIsNy41LDIsMTAuOSwwdjEuN2g0LjdjMCwwLDAtMTAuNSwwLTEwLjZjMC0zLjEsMi43LTUuNiw1LjYtNS42YzEuNiwwLDMuMywwLjYsNC4xLDEuNlYzLjdDMjkuNCwzLjMsMjcuOCwzLjEsMjYuNCwzLjF6DQoJIE01LDEzLjZMNSwxMy42YzAtMC4zLDAuMi0wLjYsMC4yLTAuOWMwLjItMS4xLDAuNi0yLjIsMS42LTNDNy44LDguNiw5LjMsOCwxMC43LDhjMC42LDAsMS4xLDAuMiwxLjcsMC4zbC03LDcNCglDNS4yLDE0LjcsNSwxNC4xLDUsMTMuNnogTTI2LjYsMi4zYzAuOCwwLDEuNiwwLDIuMywwLjNjMC0wLjIsMC0wLjIsMC0wLjJDMjguOSwxLjEsMjgsMCwyNi42LDBjLTEuNCwwLTIuNSwxLjEtMi41LDIuMw0KCWMwLDAuMiwwLDAuMiwwLDAuM0MyNC45LDIuMywyNS42LDIuMywyNi42LDIuM3ogTTM0LjEsMjEuMmMtMC4zLTAuMy0wLjgtMC41LTEuMS0wLjVjLTEuMSwwLTEuNiwwLjgtMS42LDEuNmMwLDAuOCwwLjUsMS42LDEuNiwxLjYNCgljMC4zLDAsMC44LTAuMiwwLjktMC41di0wLjloLTEuMXYtMC4yaDEuNHYxLjJjLTAuMywwLjMtMC44LDAuNi0xLjIsMC42Yy0xLjIsMC0xLjktMC45LTEuOS0xLjljMC0xLjEsMC44LTEuNywxLjktMS43DQoJYzAuNSwwLDAuOSwwLjIsMS4yLDAuNUwzNC4xLDIxLjJ6IE0zOS45LDIyLjNjMCwwLjktMC42LDEuOS0xLjcsMS45Yy0xLjIsMC0xLjktMC45LTEuOS0xLjljMC0wLjksMC42LTEuNywxLjktMS43DQoJQzM5LjIsMjAuNiwzOS45LDIxLjQsMzkuOSwyMi4zeiBNMzYuNiwyMi4zTDM2LjYsMjIuM2MwLDAuOCwwLjUsMS42LDEuNiwxLjZjMC45LDAsMS40LTAuOCwxLjQtMS42YzAtMC44LTAuNS0xLjYtMS40LTEuNg0KCUMzNywyMC44LDM2LjYsMjEuNSwzNi42LDIyLjN6IE00NC41LDIxLjVjMCwwLjMtMC4yLDAuNi0wLjYsMC44YzAuNSwwLjIsMC44LDAuNSwwLjgsMC44YzAsMC44LTAuOCwwLjktMS4yLDAuOWMtMC42LDAtMC45LDAtMS40LDANCgl2LTMuNGMwLjUsMCwwLjgsMCwxLjQsMEM0My45LDIwLjYsNDQuNSwyMC45LDQ0LjUsMjEuNXogTTQyLjIsMjIuMkw0Mi4yLDIyLjJoMS4yYzAuNSwwLDAuOC0wLjIsMC44LTAuNnMtMC41LTAuOC0wLjgtMC44aC0xLjINCglWMjIuMnogTTQyLjIsMjMuOUw0Mi4yLDIzLjloMS4yYzAuMywwLDAuOS0wLjIsMC45LTAuOGMwLTAuNS0wLjUtMC42LTAuOS0wLjZjLTAuNSwwLTAuOCwwLTEuMiwwVjIzLjl6IE00Ni43LDI0di0zLjRINDdWMjRINDYuN3oNCgkgTTQ5LjUsMjIuMmgydjAuM2gtMnYxLjRoMi4yVjI0aC0yLjN2LTMuNGgyLjN2MC4zaC0yLjJWMjIuMnogTTU2LjcsMjRoLTAuNWwtMS4xLTEuMmgtMC45VjI0aC0wLjN2LTMuNGMwLjUsMCwwLjksMCwxLjYsMA0KCWMwLjgsMCwxLjEsMC41LDEuMSwxLjFzLTAuMywxLjEtMS4xLDEuMUw1Ni43LDI0eiBNNTQuMiwyMi41TDU0LjIsMjIuNWgxLjFjMC42LDAsMC45LTAuMywwLjktMC44cy0wLjMtMC44LTAuOC0wLjhoLTEuMlYyMi41eg0KCSBNNTguNywyMC42bDIuMywyLjh2LTIuOGgwLjNWMjRoLTAuMmwtMi4zLTIuOFYyNGgtMC4zdi0zLjRINTguN3ogTTY3LDIyLjNjMCwwLjktMC42LDEuOS0xLjcsMS45Yy0xLjIsMC0xLjktMC45LTEuOS0xLjkNCgljMC0wLjksMC42LTEuNywxLjktMS43QzY2LjQsMjAuNiw2NywyMS40LDY3LDIyLjN6IE02My43LDIyLjNMNjMuNywyMi4zYzAsMC44LDAuNSwxLjYsMS42LDEuNmMwLjksMCwxLjQtMC44LDEuNC0xLjYNCgljMC0wLjgtMC41LTEuNi0xLjQtMS42QzY0LjIsMjAuOCw2My43LDIxLjUsNjMuNywyMi4zeiIvPg0KPC9zdmc+DQo=" alt="Gobierno Entre Ríos"/>
                    </td>    
                </tr>
            </table>
        </div>

    </body>
</html>