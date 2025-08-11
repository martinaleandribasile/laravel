@echo off
cd /d %~dp0
cd laravel
php artisan items:update-status
