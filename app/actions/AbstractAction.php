<?php

namespace controller\app\actions;

use controller\app\actions\get\DepartmentAction;
use controller\app\service\classes\CategoryService;
use controller\app\service\classes\ItemService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    protected array $menu;
    protected string $path;
    protected CategoryService $categoryService;

    protected DepartmentAction $departmentService;

    protected ItemService $itemService;

    public function __construct(ContainerInterface $container)
    {
        $this->menu = $container->get('menu');
        $this->path = $container->get('path');
        $this->categoryService = $container->get('category_service');
        $this->departmentService = $container->get('department_service');
        $this->itemService = $container->get('item_service');
    }

    abstract public function __invoke(Request $request, Response $response, array $args);


}
