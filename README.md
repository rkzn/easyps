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

Setup Supervisor:

* A configuration for our script, saved at /etc/supervisor/conf.d/easyps.conf
* Don't foget replace {path_to_website} in the config
```
[program:transfer-daemon]
 command={path_to_website}/app/console app:easyps:transfer-daemon
 priority=2
 redirect_stderr=true
 user=www-data
 environment=HOME="{path_to_website}",USER="www-data"
```

Demo:

* http://easyps.diamond.kazansky.su

