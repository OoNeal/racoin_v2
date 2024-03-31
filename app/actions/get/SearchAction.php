<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class SearchAction extends AbstractAction
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $twig = Twig::fromRequest($request);
        return $twig->render(
            $response, "search.html.twig", [
            "breadcrumb" => $this->menu,
            "chemin" => $this->path,
            "categories" => $this->categoryService->getCategories()
            ]
        );
    }
}
