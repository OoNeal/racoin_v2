<?php

namespace controller\app\actions\post;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
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
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $annonce = Annonce::find($args['id']);
        $annonceur = Annonceur::find($annonce->id_annonceur);
        $categItem = Categorie::find($annonce->id_categorie)->nom_categorie;
        $dptItem = Departement::find($annonce->id_departement)->nom_departement;
        $twig = Twig::fromRequest($request);

        return $twig->render(
            $response, "edit_item_post.html.twig",
            array("breadcrumb" => $this->menu,
                "chemin" => $this->path,
                "annonce" => $annonce,
                "annonceur" => $annonceur,
                "pass" => password_verify($_POST["pass"], $annonce->mdp),
                "categories" => $this->categoryService->getCategories(),
                "departements" => $this->departmentService->getDepartments(),
                "dptItem" => $dptItem,
                "categItem" => $categItem)
        );
    }
}
