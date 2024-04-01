<?php

namespace controller\app\actions;

use controller\app\service\classes\CategoryService;
use controller\app\service\classes\DepartmentService;
use controller\app\service\classes\ItemService;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    protected array $menu;
    protected string $path;
    protected CategoryService $categoryService;
    protected DepartmentService $departmentService;
    protected ItemService $itemService;
    protected Logger $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->menu = $container->get('menu');
        $this->path = $container->get('path');
        $this->categoryService = $container->get('category_service');
        $this->departmentService = $container->get('department_service');
        $this->itemService = $container->get('item_service');
        $this->logger = $container->get('logger');
    }


    abstract public function __invoke(Request $request, Response $response, array $args);


}
