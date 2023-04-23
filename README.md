# Todo List

Este es una aplicación de 'lista de tareas' CRUD construida con Laravel 10, Vue 3 y MySQL. Node.js se utiliza para eventos en tiempo real con Socket.IO.

### Usuarios (clave password)
- `john@example.com`
- `jane@example.com`
- `mike@example.com`

## Instalación

Este proyecto incluye tres subproyectos: backTodo (Laravel 10), frontTodo (Vue 3) y socketTodo (Node.js con Socket.io), necesita servir cada uno de ellos.
Debe tener instalado Composer y Node para ajustarse a los pasos
### Instalar dependencias
1. Clona el repositorio en tu máquina local:
```console
    git clone https://github.com/ultraSCAT/TodoTest.git
```
2. Navega al directorio del proyecto en tu terminal: 
```console
    cd TodoTest
```
3. Ingresa a la carpeta backTodo e instala las dependencias
```console
    cd backTodo
    composer install
```
4. Debe crear un archivo .env o utilizar el de ejemplo con el comando
```console 
    mv .env.example .env
```
5. Ejecute el siguiente comando para crear las tablas de la base de datos y agregar datos base, la clave de todos los usuarios es password.
```console
    php artisan key:generate
    php artisan migrate --seed
    php artisan db:seed --class=UserSeeder
```
5. Ahora en la carpeta frontTodo debemos instalar sus dependencias
```console
    cd ../frontTodo
    npm install
```
3. Luego se deben instalar las dependiencias del socket 
```code
    cd ../socketTodo
    npm install
```

+ ### Iniciar servidores
1. Dentro de la carpeta backTodo debe ejecutar `` php artisan serve `` este comando toma control de la consola, debe abrir una nueva para cada paso, se ejecuta en el puerto 8000.
2. En una nueva terminal y dentro de la carpeta frontTodo debera ejecutar ``npm run serve`` , se ejecuta en el puerto 8080
3. En una nueva terminal debe entrar a la carpeta socketTodo y ejecutar ``npm start``, se ejecuta en el puerto 4000

Luego de que los servidor se ejecutan puede acceder a la version dev del projecto desde este [Enlace](http://localhost:8080/)

## Descripción del proyecto

Este proyecto es una aplicación de 'lista de tareas' que permite a los usuarios agregar, eliminar y actualizar tareas. La aplicación es una interfaz de usuario simple y fácil de usar que se comunica con el servidor a través de una API RESTful.

La aplicación utiliza Laravel 10 en el lado del servidor para manejar las solicitudes de la API RESTful. MySQL se utiliza como base de datos para almacenar las tareas y su estado de finalización. La aplicación también utiliza Vue.js en el lado del cliente para crear una interfaz de usuario receptiva y fácil de usar.

Para actualizar en tiempo real la lista de tareas, la aplicación utiliza Node.js y la biblioteca Socket.IO para enviar actualizaciones a los clientes en tiempo real.

Como la aplicación se ejecuta localmente, Socket.IO utiliza Long Polling para la comunicación en tiempo real. Para un entorno de producción, se debe configurar adecuadamente para utilizar WebSockets o alguna otra estrategia de comunicación en tiempo real más eficiente.


## Paleta de colores

La paleta de colores de este proyecto se basa en los siguientes colores: `https://colorhunt.co/palette/f6e3c5a0d9956cc4a14cacbc`
- ![#F6E3C5](https://via.placeholder.com/15/F6E3C5/000000?text=+) #F6E3C5
- ![#A0D995](https://via.placeholder.com/15/A0D995/000000?text=+) #A0D995
- ![#6CC4A1](https://via.placeholder.com/15/6CC4A1/000000?text=+) #6CC4A1
- ![#4CACBC](https://via.placeholder.com/15/4CACBC/000000?text=+) #4CACBC

*Este proyecto fue creado por Ra&uacute;l Rojas*.
