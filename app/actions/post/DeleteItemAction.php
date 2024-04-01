<?php

namespace controller\app\actions\post;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Photo;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DeleteItemAction extends AbstractAction
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
        $annonce = Annonce::find($args['id']);
        $twig = Twig::fromRequest($request);
        $reponse = false;
        if(isset($_POST["pass"])) {
            if (password_verify($_POST["pass"], $annonce->mdp)) {
                $reponse = true;
                photo::where('id_annonce', '=', $args['id'])->delete();
                $annonce->delete();
            }
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
