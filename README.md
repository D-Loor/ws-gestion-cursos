# Proyecto Web Service para la gestión de cursos

Este proyecto es un Web Service desarrollado con Laravel, destinado a la gestión de cursos en una plataforma educativa.

## Requisitos

- PHP: ^8.2
- Laravel: ^11.0

## Instalación

1. Clonar el repositorio:

git clone https://github.com/D-Loor/ws-gestion-cursos.git

2. Instalar las dependencias utilizando Composer:

composer install

3. Crear un archivo `.env` a partir del archivo `.env.example` y configurar las variables de entorno necesarias (base de datos, clave de aplicación, etc.).

4. Ejecutar las migraciones de la base de datos y sembrar los datos iniciales:

php artisan migrate:fresh --seed

6. Iniciar el servidor de desarrollo:

php artisan serve


El servidor estará disponible en `http://localhost:8000`.

## Uso

Una vez que el servidor esté en funcionamiento, podrás acceder a los endpoints del Web Service utilizando herramientas como Postman, cURL o cualquier cliente HTTP.

Los endpoints disponibles y sus respectivos métodos HTTP se detallarán en la documentación del proyecto.

## Créditos

- Diego Loor (https://github.com/D-Loor)

## Contacto

Si tienes alguna pregunta o sugerencia, no dudes en ponerte en contacto conmigo:

- Correo electrónico: diego18.loor@gmail.com
