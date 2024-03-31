<?php

namespace controller\app;

use controller\app\db\connection;
use DI\ContainerBuilder;
use Exception;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$parametres = include_once __DIR__ . '/settings.php';
$routes_dependances = include_once __DIR__ . '/actions_dependencies.php';

connection::createConn();

// Initialisation de Slim
$builder = new ContainerBuilder();
$builder->addDefinitions($parametres);
$builder->addDefinitions($routes_dependances);
try {
    $c = $builder->build();
    $app = AppFactory::createFromContainer($c);
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, false, false);
    $twig = Twig::create(__DIR__ . '/../template');
    $app->add(TwigMiddleware::create($app, $twig));
    return $app;
} catch (Exception $e) {
    echo $e->getMessage();
}
