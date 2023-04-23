# Nombre del proyecto

Este es una aplicación de lista de tareas CRUD construida con Laravel 10, Vue.js y MySQL. Node.js se utiliza para las actualizaciones en tiempo real.

## Descripción del proyecto

Este proyecto es una aplicación de lista de tareas que permite a los usuarios agregar, eliminar y actualizar tareas. La aplicación es una interfaz de usuario simple y fácil de usar que se comunica con el servidor a través de una API RESTful.

La aplicación utiliza Laravel 10 en el lado del servidor para manejar las solicitudes de la API RESTful. MySQL se utiliza como base de datos para almacenar las tareas y su estado de finalización. La aplicación también utiliza Vue.js en el lado del cliente para crear una interfaz de usuario receptiva y fácil de usar.

Para actualizar en tiempo real la lista de tareas, la aplicación utiliza Node.js y la biblioteca Socket.IO para enviar actualizaciones a los clientes en tiempo real. Node.js se ejecuta en segundo plano y se comunica con la base de datos de MySQL a través de la API RESTful de Laravel.

La aplicación permite a los usuarios crear nuevas tareas, editar tareas existentes, marcar tareas como completadas y eliminar tareas. La aplicación también muestra una lista de todas las tareas en una tabla.

## Instalación

Para ejecutar la aplicación localmente, siga los siguientes pasos:

1. Clone el repositorio en su máquina local.
2. Navegue al directorio del proyecto en su terminal y ejecute `composer install` para instalar las dependencias necesarias de Laravel.
3. Ejecute `npm install` para instalar las dependencias de Node.js y Vue.js.
4. Cree un archivo `.env` en el directorio raíz y copie el contenido de `.env.example` en él.
5. Actualice el archivo `.env` con los detalles de conexión de su base de datos MySQL.
6. Ejecute `php artisan migrate` para crear las tablas de la base de datos.
7. Ejecute `npm run dev` para compilar los activos de Vue.js.
8. Ejecute `php artisan serve` para iniciar el servidor de Laravel.
9. Ejecute `node server.js` para iniciar el servidor Node.js para las actualizaciones en tiempo real.

## Uso

Una vez que la aplicación esté en funcionamiento, puede crear nuevas tareas, editar tareas existentes, marcar tareas como completadas y eliminar tareas. La aplicación también muestra una lista de todas las tareas en una tabla. Las actualizaciones en tiempo real se enviarán automáticamente a todos los clientes cuando se agregue, edite o elimine una tarea.

## Créditos

Este proyecto fue creado por [Tu nombre].

## Licencia

Este proyecto está bajo la Licencia MIT.
