<?php

namespace controller\app\actions;

use controller\app\actions\get\DepartmentAction;
use controller\app\model\Categorie;
use controller\app\service\classes\CategoryService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    protected array $menu;
    protected string $path;
    protected Categorie $category;

    protected CategoryService $categoryService;

    protected DepartmentAction $departmentService;

    protected ObjetControlleur $objectService;

    public function __construct(ContainerInterface $container)
    {
        $this->menu = $container->get('menu');
        $this->path = $container->get('path');
        $this->category = $container->get('category');
        $this->categoryService = $container->get('category_service');
        $this->departmentService = $container->get('department_service');
        $this->objectService = $container->get('object_service');
    }

    abstract public function __invoke(Request $request, Response $response, array $args);


}