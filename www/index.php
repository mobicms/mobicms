<?php

declare(strict_types=1);

use HttpSoft\Basis\Application;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Container\ContainerInterface;

define('START_MEMORY', memory_get_usage());
define('START_TIME', microtime(true));

chdir('../');
require 'vendor/autoload.php';

(function () {
    /** @var ContainerInterface $container */
    $container = require_once 'config/container.php';
    /** @var Application $app */
    $app = $container->get(Application::class);

    (require_once 'config/pipeline.php')($app);
    (require_once 'config/routes.php')($app);

    $app->run(ServerRequestCreator::create());
})();
