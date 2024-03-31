<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ApiHomeAction extends AbstractAction
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $twig = Twig::fromRequest($request);

        $menu = array(
            $this->menu,
            array(
                'href' => $this->path . '/api',
                'text' => 'Api'
            )
        );

        return $twig->render($response, 'api.html.twig',
            array(
                'breadcrumb' => $menu,
                'chemin' => $this->path
            )
        );
    }
}