![image](api/resources/image/readme.jpg)

I configured the docker environment to facilitate testing, but if you want to run on your machine manually you can use this document as a basis, but also the Lumen documentation available at: https://lumen.laravel.com/docs/8.x

![image](api/resources/image/how_to_run.jpg)

## The Easiest Way (with docker-compose)
After clone this project, please enter in your favorite terminal the follow:
````textbash
$ cd lumen-api
$ docker-compose up -d
````
To quit:
````textbash
$ docker-compose down
````
Api is running in port localhost:80

##Requirements:
````
docker-compose
````
---
## Traditional Way
###1 - Install composer
````textbash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
````
###2 - config project
````textbash
composer install

php -r "copy('.env.example', '.env');"

php artisan key:generate

php artisan migrate
````
###3 - Configure database
configure a database and update .env file in project folder

###4 - Run Project
````textbash
php -S 0.0.0.0:80 -t ./public
````
Api is running in port localhost:80

## Requirements - Traditional
````
PHP >= 7.3
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
````