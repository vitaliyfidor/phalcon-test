<?php

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    define('APP_PATH', realpath('..') . '/');

    /**
     * Read the configuration
     */
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');

    /**
     * Read auto-loader
     */
    require APP_PATH . 'app/config/loader.php';

    /**
     * Read services
     */
    require APP_PATH . 'app/config/services.php';

    /**
     * Handle the request
     */
    $application = new Application();
    $application->setDI($di);
    echo $application->handle()->getContent();
} catch (Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
} catch (PDOException $e) {
    echo $e->getMessage();
}
