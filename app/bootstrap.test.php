<?php
requireComposer();
requireBootstrap();

function requireComposer()
{
    if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
        foreach (array(__DIR__ . '/autoload.php', __DIR__ . '/../vendor/autoload.php') as $file) {
            if (file_exists($file)) {
                define('PHPUNIT_COMPOSER_INSTALL', $file);
                break;
            }
        }

        if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
            die(
                'You need to set up the project dependencies using the following commands:' . PHP_EOL .
                'wget http://getcomposer.org/composer.phar' . PHP_EOL .
                'php composer.phar install' . PHP_EOL
            );
        }

        require PHPUNIT_COMPOSER_INSTALL;
    }
}

function requireBootstrap()
{
    $parsed = file(dirname(__FILE__) . '/config/parameters.yml');
    unset($parsed[0]);
    $parsed[1] = '[parameters]';
    $parsed = array_map(
        function ($value) {
            return str_replace(':', '=', $value);
        },
        $parsed
    );
    $parsed = parse_ini_string(implode("\n", $parsed));

    $_SERVER['KERNEL_DIR'] = __DIR__;
//    $_SERVER['HTTP_HOST'] = $parsed['hosts.root'];
    $_SERVER['SYMFONY__IS__PHPUNIT'] = true;

    require_once __DIR__.'/../app/autoload.php';
}
