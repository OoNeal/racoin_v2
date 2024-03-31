<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ModifyItemAction extends AbstractAction
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
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $annonce = Annonce::find($args['id']);
        $twig = Twig::fromRequest($request);

        if (!isset($annonce)) {
            $response->getBody()->write("Annonce non trouvée");
            return $response->withStatus(404);
        }

        return $twig->render(
            $response, "edit_item_get.html.twig",
            array("breadcrumb" => $this->menu,
                "chemin" => $this->path,
                "annonce" => $annonce)
        );
    }
}
