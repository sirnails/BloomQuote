call composer install

pause

call composer dump -o

pause 

call ./vendor/bin/phpunit --list-tests

pause

call ./vendor/bin/phpunit --testdox

REM vendor/bin/phpunit

pause

call composer install --no-dev --optimize-autoloader --classmap-authoritative

pause

call composer dump -o