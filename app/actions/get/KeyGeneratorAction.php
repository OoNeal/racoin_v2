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

class KeyGeneratorAction extends AbstractAction
{

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $twig = Twig::fromRequest($request);
        $menu = array(
            array('href' => $this->path,
                'text' => 'Acceuil'),
            array('href' => $this->path . "/search",
                'text' => "Recherche")
        );
        return $twig->render(
            $response, "key_generator.html.twig",
            array(
                "breadcrumb" => $menu,
                "chemin" => $this->path,
                "categories" => $this->categoryService->getCategories()
            ),
        );
    }
}
