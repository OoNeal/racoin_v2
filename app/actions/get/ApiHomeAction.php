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

class ApiHomeAction extends AbstractAction
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
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $twig = Twig::fromRequest($request);

        $menu = array(
            $this->menu,
            array(
                'href' => $this->path . '/api',
                'text' => 'Api'
            )
        );

        $this->logger->info("Api");
        return $twig->render(
            $response, 'api.html.twig',
            array(
                'breadcrumb' => $menu,
                'chemin' => $this->path
            )
        );
    }
}
