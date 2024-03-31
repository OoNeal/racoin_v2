<?php


use controller\app\actions\get\DepartmentAction;
use controller\app\service\classes\CategoryService;
use controller\app\service\classes\ItemService;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Monolog\Logger;

return [
    'menu' => [
        [
            'href' => dirname($_SERVER['SCRIPT_NAME']),
            'text' => 'Accueil'
        ]
    ],
    'path' => dirname($_SERVER['SCRIPT_NAME']),
    'category_service' => new CategoryService,
    'department_service' => new DepartmentAction,
    'item_service' => new ItemService,

    'log.order.name' => 'order.log',
    'log.order.file' => __DIR__ . '../logs/order.log',
    'log.order.level' => Level::Info,
    'logger' => function (ContainerInterface $container) {
        $logger = new Logger($container->get('log.order.name'));
        $logger->pushHandler(new StreamHandler($container->get('log.order.file'), $container->get('log.order.level')));
        return $logger;
    },

];
