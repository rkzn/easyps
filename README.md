EasyPS test app
===============

Instructions to run the app:

* clone project
* create MySQL database for app and fill parameters in app/config/parameters.yml 
* run `composer install`
* run `php app/console doctrine:schema:update --force`
* run `php app/console doctrine:fixtures:load`
* run `php app/console server:run 127.0.0.1:8001`
* open http://127.0.0.1:8001/app_dev.php/ in your browser

Instructions to run the tests:

* run `php app/runtest -c app/phpunit.xml`

Demo:

* http://easyps.diamond.kazansky.su

