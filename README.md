# koi-auth
Authentication API service written using Slim. 
The idea is that the developer is the gardener, and the gardener may have multiple ponds (apps) where he may keep his koi (users).

![Home page](https://raw.githubusercontent.com/tomual/koi-auth/master/images/56f54acd5e.png)

## Features

* API key & API secret authorization
* API call to create user
* API call to log in user
* Developer may have multiple apps
* Rate limiting (per day)
* Dashboard for each pond to show number of calls made, and list of users

## Installation
1. Place files in server running PHP 7.0+
2. `composer install` to install packages
3. Modify `bootstrap/app.php` to insert database credentials
4. Run `sql/tables.sql` to create tables

## Screenshots

Here is an API call made to the registration endpoint
![API call](https://raw.githubusercontent.com/tomual/koi-auth/master/images/75eb1d43cb.png)
