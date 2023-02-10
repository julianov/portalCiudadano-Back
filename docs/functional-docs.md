# Documentación funcional

## Índice

- [Introducción](#introducción)
- [Bloques de funcionalidad](#bloques-de-funcionalidad)
    - [Autenticación](#autenticación)
        - [Registro](#registro-de-usuarios)

# Introducción

Este documento debe ser leído por cualquier persona que quiera entender el funcionamiento de la aplicación, de ser posible en su totalidad.

# Bloques de funcionalidad

# Autenticación

Esta sección consta de 4 etapas:

1. [Registro de usuarios](#registro-de-usuarios)
1. [Confirmación de email](#confirmación-de-email)
1. [Inicio de sesión](#inicio-de-sesión)
1. [Recuperación de contraseña](#recuperación-de-contraseña)

# Registro de usuarios

## Frontend

### Definición

El proceso de registro consta de un formulario con los siguientes campos y condiciones:

- CUIL: 
    - Requerido: sí
    - Tipo: texto
    - Placeholder: "11000000001"
    - Nombre del formulario: cuil
    - Validaciones:
        - Longitud: 11
        - Tipo: numérico
    - Mensaje de error: "El CUIL debe tener 11 dígitos"
- (XXX - A completar): 
    - Requerido: sí
    - Tipo: texto
    - Placeholder: (XXX - A completar)
    - Nombre del formulario: prs_id
    - Validaciones:
        - (XXX - A completar)
        - (XXX - A completar)
    - Mensaje de error: (XXX - A completar)
- Nombre: 
    - Requerido: sí
    - Tipo: texto
    - Placeholder: "Juan"
    - Nombre del formulario: nombre
    - Validaciones:
        - Longitud: (XXX - A completar)
        - Tipo: alfabético
    - Mensaje de error: (XXX - A completar)
- Apellido: 
    - Requerido: sí
    - Tipo: texto
    - Placeholder: "Perez"
    - Nombre del formulario: apellido
    - Validaciones:
        - Longitud: (XXX - A completar)
        - Tipo: alfabético
    - Mensaje de error: (XXX - A completar)
- Email: 
    - Requerido: sí
    - Tipo: email
    - Placeholder: "ejemplo@ejemplo.com"
    - Nombre del formulario: email
    - Validaciones:
        - Tipo: email
    - Mensaje de error: (XXX - A completar)
- Contraseña: 
    - Requerido: sí
    - Tipo: contraseña
    - Placeholder: ""
    - Nombre del formulario: password
    - Validaciones:
        - Longitud: (XXX - A completar)
        - Tipo: texto

### Flujo

XXX - A completar

## Backend

### Definición

El backend se encarga de recibir los datos del formulario y validarlos. Si los datos son válidos, se crea un usuario en la base de datos y se envía un email de confirmación al usuario. Si los datos no son válidos, se envía un mensaje de error al frontend.

### Validaciones

Las validaciones se realizan en el backend y se envían al frontend en caso de que no se cumplan. Las validaciones son las siguientes:

- cuil: 
    - Requerido: sí [Error: "El campo CUIL es obligatorio"]
    - Longitud: 11 [Error: "El CUIL debe tener 11 dígitos"]
    - Tipo: texto (numérico) [Error: "El CUIL debe ser de tipo texto (numérico)"]
- prs_id: 
    - Requerido: sí [Error: "El campo prs_id es obligatorio"]
    - Otras validaciones: ?????
- nombre:
    - Requerido: sí [Error: "El campo Nombre es obligatorio"]
    - Tipo: texto (alfabético) [Error: "El Nombre debe ser de tipo texto (alfabético)"]
    - Otras validaciones: ????????????
- apellido:
    - Requerido: sí [Error: "El campo Apellido es obligatorio"]
    - Tipo: texto (alfabético) [Error: "El Apellido debe ser de tipo texto (alfabético)"]
    - Otras validaciones: ????????????
- email:
    - Requerido: sí [Error: "El campo Email es obligatorio"]
    - Tipo: email [Error: "El Email debe ser de tipo email"]
- password:
    - Requerido: sí [Error: "El campo Contraseña es obligatorio"]
    - Otras validaciones: ????????????

Ante cualquier error, el backend envía la respuesta predefinida por el framework Laravel con el formato correspondiente.

### Flujo
1. Recepción de datos del formulario
1. Validación de datos
    - Datos inválidos: Responde con *ErrorDeValidacion* (error predefinido por el framework Laravel)
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

1. Error de validación
1. Usuario ya registrado
1. CUIL inválido
1. Datos inconsistentes
1. Error interno del servidor
