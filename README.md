# simple-cart
Simple Cart &amp; Order checkout using Symfony (PHP)

## Requirements
- PHP 7.2
  - PHP MySQL driver
- Composer

## Setup
- Clone project & Change to project directory
- Update `DATABASE_URL` in `.env` file to suite your local environment
- Run `composer install` to install required symfony, doctrine & web-server dependencies
- Setup Database:
  - Run `php bin/console doctrine:database:create` to create database as per `.env` setting
  - Run `php bin/console doctrine:migrations:migrate` to create entity tables
- Run `phpunit` to run tests
- Run `php bin/console server:run 127.0.0.1:8080` to view in local browser
  - Browse to `http://127.0.0.1:8080` and ensure you receive the home page.
  - If no products are loaded on the home page, browse to the following url to initialise sample product data:
    - http://localhost:9000/initialise-product-data
