@echo off
setlocal

call composer install

call composer dump -o

call ./vendor/bin/phpunit --list-tests

call ./vendor/bin/phpunit --testdox

:ask
echo Do you want to remove Composer dev tools ready for production? (Y/N)
set /p choice=

if /i "%choice%"=="Y" goto :remove
goto :exit

:remove
echo Removing Composer dev tools...
call composer install --no-dev --optimize-autoloader --classmap-authoritative
goto :end

:exit
echo No changes have been made.

:end
call composer dump -o