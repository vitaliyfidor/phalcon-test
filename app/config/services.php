<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View\Engine\Php as PhpViewEngine;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Mvc\Router;

/**
 * The FactoryDefault Dependency Injector automatically registers the right
 * services to provide a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set(
    "url",
    function () use ($config) {
        $url = new UrlResolver();

        $url->setBaseUri(
            $config->application->baseUri
        );

        return $url;
    },
    true
);

/**
 * Setting up the view component
 */
$di->set(
    "view",
    function () use ($config) {

        $view = new View();

        $view->setViewsDir(
            APP_PATH . $config->application->viewsDir
        );

        $view->registerEngines(
            [
                ".volt" => function ($view, $di) use ($config) {
                    $volt = new VoltEngine($view, $di);

                    $volt->setOptions(
                        [
                            "compiledPath"      => APP_PATH . $config->application->cacheDir,
                            "compiledSeparator" => "_",
                            "compileAlways"     => true,
                        ]
                    );

                    return $volt;
                },

                // Generate Template files uses PHP itself as the template engine
                ".phtml" => PhpViewEngine::class
            ]
        );

        return $view;
    },
    true
);

/**
 * Database connection is created based on the parameters defined in the
 * configuration file
 */
$di->set(
    'db',
    function () use ($config) {
        return new DbAdapter(
            [
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->dbname,
            ]
        );
    }
);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set(
    "modelsMetadata",
    function () use ($config) {
        return new MetaDataAdapter();
    }
);

/**
 * Start the session the first time some component request the session service
 */
$di->set(
    "session",
    function () {
        $session = new SessionAdapter();

        $session->start();

        return $session;
    }
);

/**
 * Dispatcher
 */
$di->set(
    "dispatcher",
    function () {

        $eventsManager = new EventsManager;

        /**
         * Check if the user is allowed to access certain action using the SecurityPlugin
         */
        $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
        /**
         * Handle exceptions and not-found exceptions using NotFoundPlugin
         */
        $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

        $dispatcher = new Dispatcher;
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    }
);

/**
 * Set up the flash service
 */
$di->set(
    "flash",
    function () {
        $flash = new FlashDirect(
            [
                "error"   => "alert alert-danger",
                "success" => "alert alert-success",
                "notice"  => "alert alert-info",
                "warning" => "alert alert-warning",
            ]
        );

        return $flash;
    }
);

/**
 * Set up router
 */
$di->set('router', function () {
    $router = new Router(false);

    $router->removeExtraSlashes(true);
    $router->setDefaultController('user');
    $router->setDefaultAction('index');

    /**
     * Standard MVC routes
     */
    $router->add('/', []);

    $router->add(
        '/:controller',
        [
            'controller' => 1
        ]
    );

    $router->add(
        '/:controller/:action/:params',
        [
            'controller' => 1,
            'action'     => 2,
            'params'     => 3
        ]
    );

    return $router;
});

