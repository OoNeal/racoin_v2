<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SearchAction extends AbstractAction
{

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): \Psr\Http\Message\ResponseInterface
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
