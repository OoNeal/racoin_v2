<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use controller\app\model\Photo;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ItemAction extends AbstractAction
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

        $annonce = Annonce::find($args['id']);
        if (!isset($annonce)) {
            return $response->withStatus(404);
        }
        $annonceur = Annonceur::find($annonce->id_annonceur);
        $departement = Departement::find($annonce->id_departement);
        $photo = Photo::where('id_annonce', '=', $args['id'])->get();

        $menu = array(
            $this->menu,
            array('href' => $this->path . "/cat/" . $args['id'],
                'text' => Categorie::find($annonce->id_categorie)?->nom_categorie),
            array('href' => $this->path . "/item/" . $args['id'],
                'text' => $annonce->titre)
        );

        return $twig->render(
            $response, "item.html.twig", [
            "breadcrumb" => $menu,
            "chemin" => $this->path,
            "annonce" => $annonce,
            "annonceur" => $annonceur,
            "dep" => $departement->nom_departement,
            "photo" => $photo,
            "categories" => $this->path
            ]
        );

    }
}
