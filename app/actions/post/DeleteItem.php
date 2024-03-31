<?php

namespace controller\app\actions\post;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Photo;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class DeleteItem extends AbstractAction
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $annonce = Annonce::find($args['id']);
        $twig = Twig::fromRequest($request);
        $reponse = false;
        if (password_verify($_POST["pass"], $annonce->mdp)) {
            $reponse = true;
            photo::where('id_annonce', '=', $args['id'])->delete();
            $annonce->delete();
        }

        return $twig->render(
            $response, "delete_item_post.html.twig",
            array("breadcrumb" => $this->menu,
                "chemin" => $this->path,
                "annonce" => $annonce,
                "pass" => $reponse,
                "categories" => $this->categoryService->getCategories()
            )
        );
    }
}
