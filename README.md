# rendu_symfony

composer install

modifier le fichié .env

php bin/console doctrine:database:create

php bin/console make:migration

php bin/console doctrine:migrations:migrate 

symfony server:start