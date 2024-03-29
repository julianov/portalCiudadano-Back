# Documentación funcional

## Índice

- [Introducción](#introducción)
- [Bloques de funcionalidad](#bloques-de-funcionalidad)
    - [Autenticación](#autenticación)
        - [Registro](#registro-de-usuarios)
        - [Autocomplete de Registro](#autocomplete-de-registro-de-usuarios)
        - [Confirmación de email](#confirmación-de-email)
        - [Reenvío de email de confirmación](#reenvío-de-email-de-confirmación)
        - [Inicio de sesión](#inicio-de-sesión)
        - [Solicitud de reinicio de contraseña](#solicitud-de-reinicio-de-contraseña)
        - [Reinicio de contraseña](#Reinicio-de-contraseña)
    - [Usuarios](#usuarios)
        - [Verificación de CUIL](#verificación-de-cuil)

# Introducción

Documentación funcional del proyecto. Entiéndase como la documentación **no-técnica** que provee amplia información sobre el funcionamiento de la aplicación pero bajo nignún concepto provee información detallada sobre su implementación. Si se desea conocer la implementación detallada de un proceso en particular, éste no es el lugar correcto y deberá dirigirse a la documentación **técnica** en su sección pertinente.

# Bloques de funcionalidad

Los bloques de funcionalidad son las funcionalidades que componen la aplicación. Cada bloque de funcionalidad tiene su propia sección en este documento. Seguidamente se listan dichos bloques para mayor comodidad:

- [Autenticación](#autenticación)
- [Usuarios](#usuarios)
# Autenticación

Esta sección consta de las siguientes funcionalidades:

1. [Registro de usuarios](#registro-de-usuarios)
1. [Autocomplete de Registro de usuarios](#autocomplete-de-registro-de-usuarios)
1. [Confirmación de email](#confirmación-de-email)
1. [Reenvío de email de confirmación](#reenvío-de-email-de-confirmación)
1. [Inicio de sesión](#inicio-de-sesión)
1. [Solicitud de reinicio de contraseña](#solicitud-de-reinicio-de-contraseña)
1. [Reinicio de contraseña](#reinicio-de-contraseña)

# Registro de usuarios


## Frontend

### Definición

XXX - A completar

### Flujo

XXX - A completar

## Backend
<!-- signup -->

### Definición

El *backend* se encarga de recibir los datos del formulario y validarlos. Si los datos son válidos, se crea un usuario en la base de datos y se envía un email de confirmación al usuario. Si los datos no son válidos, se envía un mensaje de error al frontend.

### Validaciones

Las validaciones se realizan en el *backend* y se envían al frontend en caso de que no se cumplan. Las validaciones son las siguientes:

- cuil: 
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
- prs_id: 
    - Requerido: sí [Error: "El campo prs_id es obligatorio"]
- nombre:
    - Requerido: sí [Error: "El campo Nombre es obligatorio"]
- apellido:
    - Requerido: sí [Error: "El campo Apellido es obligatorio"]
- email:
    - Requerido: sí [Error: "El campo Email es obligatorio"]
- password:
    - Requerido: sí [Error: "El campo Contraseña es obligatorio"]

Ante cualquier error, el *backend* envía el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo
1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error de **DatosInvalidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Verificar si el usuario ya existe en la base de datos
    - El usuario ya existe: Responde con el error **Usuario ya registrado**
    - El usuario no existe: Continúa con el flujo
1. Verificar si es el CUIL es un CUIL válido con el servicio de *Entre Río Web Services*
    - El CUIL no es válido: Responde con el error **CUIL inválido**
    - El CUIL es válido: Continúa con el flujo
1. Verificar consistencia de datos entre el CUIL reportado por *Entre Río Web Services* y el ingresado por el usuario
    - Los datos no son consistentes: 
        - Se borra el usuario de la base de datos
        - Responde con el error **Datos inconsistentes**
    - Los datos son consistentes: Continúa con el flujo
1. Se registra el usuario en la base de datos
1. Se genera un token de confirmacion de email y se guarda en la base de datos
1. Se envía un email de confirmación de registro al usuario
1. Responde con el mensaje **Usuario registrado**

### Errores

1. Datos inválidos
1. Usuario ya registrado
1. CUIL inválido
1. Datos inconsistentes
1. Error interno del servidor

# Autocomplete de Registro de usuarios

## Frontend

XXX - A Completar

## Backend
<!-- checkUserCuil -->
### Definición

Obtiene los datos personales del usuario en base a un CUIL del servicio de información de Entre Ríos.

### Validaciones

- cuil:
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Longitud min: 11 [Error: "El CUIL debe tener 11 dígitos"]
    - Longitud max: 11 [Error: "El CUIL debe tener 11 dígitos"]

Cualquier error, el *backend* responde con el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Verifico preexistencia de usuario con ese CUIL
    - existe el usuario: error **Usuario registrado/existente**
    - no existe el usuario => continua el flujo
1. Se intenta buscar los campos en con el servicio de Entre Rios
    - No existe una persona con ese CUIL: error **CUIL erroneo**
    - Si existe una persona con ese CUIL: Continua el flujo
1. Verifico si es Actor (actor significa funcionario público)
    - No es actor: Retorna los datos y "actor:false"
    - Si es actor: Retorna los datos y "actor:true"

### Errores

1. Datos inválidos
1. Usuario registrado/existente
1. CUIL erroneo
1. Error interno del sistema

# Confirmación de email

## Frontend

XXX - A Completar

## Backend
<!-- validateNewUser -->

### Definición

Se valida el token

### Validaciones

- token:
    - Requerido: sí [Error: "El campo TOKEN es obligatorio"]

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se busca el usuario en la base de datos en funcion de su CUIL
    - El usuario no existe: Responde con el error **Usuario no encontrado**
    - El usuario existe: Continúa con el flujo
1. Se verifica que el token provisto es el mismo que el almacenado en la base de datos
    - El token no es válido: Responde con el error **Error token de confirmación (token inválido)**
    - El token es válido: Continúa con el flujo
1. Se actualiza el estado del usuario a "confirmado" y se le asigna un nivel de acceso (1 o 4)
1. Se responde con el mensaje **Email de usuario confirmado**

### Errores

1. Datos inválidos
1. Usuario no encontrado
1. Error token de confirmación (token invalido)
1. Error interno del servidor

# Reenvío de email de confirmación

## Frontend

XXX - A Completar

## Backend
<!-- resendEmailVerification -->

### Definición

Se verifica que el usuario no esté confirmado y se envía un nuevo email de confirmación. Intervalo entre reenvíos: 5 minutos.

### Validaciones

- cuil:
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Longitud min: 11 [Error: "El CUIL debe tener 11 dígitos"]
    - Longitud max: 11 [Error: "El CUIL debe tener 11 dígitos"]

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se busca el usuario en la base de datos en funcion de su CUIL
    - El usuario no existe: Responde con el error **Cuil inválido**
    - El usuario existe: Continúa con el flujo
1. Se verifica que el usuario no esté confirmado
    - El usuario está confirmado: Responde con **El usuario ya está confirmado**
    - El usuario no está confirmado: Continúa con el flujo
1. Se verifica el tipo de usuario y se genera un token de confirmación
1. Se guarda el token en la base de datos
1. Se envía un email de confirmación al usuario

# Inicio de sesión

## Frontend

XXX - A Completar

## Backend
<!-- login -->

### Definición

El usuario se valida con su CUIL y contraseña. Si los datos son correctos, (XXX - A completar).

### Validaciones

- cuil: 
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Longitud: 11 [Error: "El CUIL debe tener 11 dígitos"]
    - Tipo: texto (numérico) [Error: "El CUIL debe ser de tipo texto (numérico)"]
- password:
    - Requerido: sí [Error: "El campo Contraseña es obligatorio"]

Cualquier error, el *backend* responde con el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se intenta autenticar al usuario
    - El usuario no puede ser autenticado: Responde con el error **Credenciales erróneas**
    - El usuario existe: Continúa con el flujo
1. Se verifica que el usuario haya realizado el proceso de confirmación de registro mediante confirmación de email
    - El usuario no ha confirmado su email: Responde con el error **Email no confirmado**
    - El usuario ha confirmado su email: Continúa con el flujo
1. Se obtiene el nivel de acceso del usuario
    - El usuario no tiene nivel de acceso asignado: Responde con el error **Error interno del sistema** 
    - El usuario tiene nivel de acceso: Continúa con el flujo
1. Se genera un token de acceso en base al nivel de acceso del usuario con vencimiento de 1 (un) día
1. Se responde con el token de acceso y la data necesaria en el *frontend*

### Errores

1. Datos inválidos
1. Credenciales erróneas
1. Email no confirmado
1. Error interno del sistema

# Solicitud de reinicio de contraseña

## Frontend
XXX - A Completar

## Backend
<!-- passwordResetValidation -->

### Definición

Se valida el CUIL y se envía un email con un token para el reinicio de contraseña.

### Validaciones

- cuil: 
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Longitud: 11 [Error: "El CUIL debe tener 11 dígitos"]
    - Tipo: texto (numérico) [Error: "El CUIL debe ser de tipo texto (numérico)"]

Cualquier error, el *backend* responde con el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se busca el usuario y sus datos de autenticación en la base de datos en funcion de su CUIL
1. Verificación de existencia previa de un token de reinicio de contraseña
    - Existe un token de reinicio de contraseña: 
        - Actualiza el token de reinicio de contraseña
        - Continua con el flujo
    - No existe un token de reinicio de contraseña: 
        - Genera uno nuevo
        - Continua con el flujo
1. Envío de email con el token de reinicio de contraseña
1. Responde con el mensaje **Email de reinicio de contraseña enviado**

### Errores

1. Datos inválidos
1. Error interno del servidor

# Reinicio de contraseña

## Frontend

XXX - A Completar

## Backend
<!-- passwordReset -->

### Definición

Cambia la contraseña del usuario si el token para dicho proceso es válido.

### Validaciones

- token:
    - Requerido: sí [Error: "El campo TOKEN es obligatorio"]
- new_password:
    - Requerido: sí [Error: "El campo Nueva Contraseña es obligatorio"]

Cualquier error, el *backend* responde con el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se busca el usuario en la base de datos en funcion de su CUIL y se obtiene sus datos de autenticación
1. Se verifica que el token provisto por el usuario es el mismo que el generado por el sistema
    - El token no es válido: Responde con el error **Token inválido**
    - El token es válido: Continúa con el flujo
1. Se actualiza la contraseña del usuario
1. Se responde con el mensaje **Contraseña actualizada**

### Errores

1. Datos inválidos
1. Token inválido
1. Error interno del sistema

# Usuarios

Esta sección consta de las siguientes funcionalidades:

1. [Obtención de los datos personales](#obtención-de-los-datos-personales)
1. [Eliminar cuenta](#eliminar-cuenta)

# Actualización de los datos personales

## Frontend

XXX - A Completar

## Backend
<!-- personalData -->

### Definición

Verifica si el CUIL provisto por el usuario es válido.

### Validaciones

- cuil:
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Tipo: texto (numérico) [Error: "El CUIL debe ser de tipo texto (numérico)"]
    - Longitud: 11 [Error: "El CUIL debe tener 11 dígitos"]

Cualquier error, el *backend* responde con el error **Datos inválidos**, la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo

1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con error **Datos inválidos** (error predefinido por el framework Laravel)
    - Datos válidos: Continúa con el flujo
1. Se verifica que el CUIL provisto por el usuario sea válido

### Errores

1. Datos inválidos
1. Error interno del sistema

# Eliminar usuario

## Frontend

XXX - A Completar

## Backend
<!-- eliminarUser -->

### Definición

Elimina la cuenta del usuario. (Esta funcionalidad es unicamente para **TESTING** y deberá ser eliminada en producción)
