# PHP MVC framework
Minimalistic custom framework created for educational purposes.



## The framework is not well tested on production. If you use it on production you use it on your own risk.
**Related core package**: https://github.com/hosseinabdollahzadeh/abd-php-mvc-core

----
## Installation

1. Download the archive or clone the project using git
2. Create database schema
3. Create `.env` file from `.env.example` file and adjust database parameters (including schema name)
4. Run `composer install`
5. Run migrations by executing `php migrations.php` from the project root directory
6. Go to the `public` folder 
7. Start php server by running command `php -S 127.0.0.1:8080` 
8. Open in browser http://127.0.0.1:8080

------
## Installation using docker
Make sure you have docker installed. <br>
Make sure `docker` and `docker-compose` commands are available in command line.

1. Clone the project using git
2. Copy `.env.example` into `.env` (Don't need to change anything for local development)
3. Navigate to the project root directory and run `docker-compose up -d`
4. Install dependencies - `docker-compose exec app composer install`
5. Run migrations - `docker-compose exec app php migrations.php`
6. Open in browser http://127.0.0.1:8080

