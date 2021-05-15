#  Taller de introducción a symfony 5
Proyecto desarrollado en el taller de Symfony 5


***Funcionando en PHP 7.4.14 y PHP 8.0.6***

## Tabla de contenido
- [Descripción del proyecto](#descripción)
- [Requerimientos](#requisitos)
- [Instalación del proyecto](#instalación)




## Descripción

Elabore una aplicación de lista de tareas pendientes que sean asignadas al usuario. 

La aplicación contará con un panel de identificación (login) para los usuarios y su panel de creación de registro de usuarios. Las tareas serán asignadas por el jefe del departamento (crud).

El usuario para terminar la tarea debe marcarla como finalizada, al asignarle una tarea a un usuario será notificado mediante correo electrónico. 

La interfaz para el usuario debe contar las tareas pendientes y tareas completadas. Dichas tareas serán asignadas en un nivel de prioridad de 1 a 5, siendo 1 el nivel menor de prioridad y 5 el mayor nivel.

Al generar la tarea se debe crear un título, descripción, nivel de prioridad, opción para archivo adjunto y usuario destinatario. 

Para el administrador, tiene la opción de generar un archivo PDF con las tareas sin terminar incluyendo los datos de cada tarea. Los usuarios podrán realizar sugerencias en un formulario de forma anónima.


## Requisitos
- Servidor local
- PHP 7.2.5 o superior
- Composer
- Symfony CLI


## Instalación
### 1 Paso(Descarga del proyecto)
 - **1 forma**. Dar clic en Code y luego en Donwload Zip

 - **2forma**. Crear una carpeta, ingresar a git bash y ejecutar
```
    git clone https://github.com/Rodrigo507/tallerfiec.git
```
***Para los siguientes pasos, aceder a la carpeta que se creo luego de descargar o clonar el proyecto "tallerfiec" ***
### 2 Paso
- Instalar dependencias 

 ```bash
composer install
```


### 3 Paso(Configuración BD)

- Configurar acceso para la base de datos en el archivo **.env**
  ```
  DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
  ```
- Crear base de datos
  ```
  php bin/console doctrine:database:create
  ```

### 4 Levantar servidr
  ```
  symfony server:start -d
  ```
### 5 Acceder al proyecto
[localhost:8080](http://127.0.0.1:8000/)


## Author

👤 **Rodrigo Gutierrez**

* Github: [@Rodrigo507](https://github.com/Rodrigo507)
