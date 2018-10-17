#!/bin/bash

nameFolder="${PWD##*/}"

echo "run docker"
docker-compose -p $nameFolder up --build -d &&

echo "storage chmod 777"
docker exec ${nameFolder}_app_1 chmod 777 -R storage/ &&

echo "php artisan key:generate"
docker exec ${nameFolder}_app_1 php artisan key:generate &&

echo "create database abz if mot exists"
docker exec -i ${nameFolder}_db_1 mysql -u abz --password=abz -e "CREATE DATABASE IF NOT EXISTS abz" &&

echo "load dump  to database abz"
docker exec -i ${nameFolder}_db_1 mysql -u abz --password=abz abz  < dump.sql

echo go to site!