#!/usr/bin/env bash
php artisan down
git pull origin master
composer install
php artisan migrate
php artisan db:update
php artisan l5-swagger:generate
sudo chmod -R 777 storage/
yarn install
yarn run production
php artisan up
