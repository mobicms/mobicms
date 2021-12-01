<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;

chdir(dirname(__DIR__));
require 'system/vendor/autoload.php';

(function () {
    /** @var Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var Application $app */
    $app = $container->get(Application::class);

    /** @var MiddlewareFactory $factory */
    $factory = $container->get(MiddlewareFactory::class);

    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
