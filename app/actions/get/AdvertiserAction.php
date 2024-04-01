<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Photo;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdvertiserAction extends AbstractAction
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
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $annonceur = annonceur::find($args['id']);
        $twig = Twig::fromRequest($request);
        if (!isset($annonceur)) {
            $response->getBody()->write("Annonceur non trouvé");
            return $response;
        }

        $annonces = $this->getAnnonces($args['id']);

        return $twig->render(
            $response, "advertiser.html.twig",
            array('nom' => $annonceur,
                "chemin" => $this->path,
                "annonces" => $annonces,
                "categories" => $this->categoryService->getCategories()
            )
        );
    }

    /**
     * @param $id_annonceur
     * @return array
     */
    private function getAnnonces($id_annonceur): array
    {
        $tmp = annonce::where('id_annonceur', '=', $id_annonceur)->get();

        $annonces = [];
        foreach ($tmp as $a) {
            $a->nb_photo = Photo::where('id_annonce', '=', $a->id_annonce)->count();
            if ($a->nb_photo > 0) {
                $a->url_photo = Photo::select('url_photo')
                    ->where('id_annonce', '=', $a->id_annonce)
                    ->first()->url_photo;
            } else {
                $a->url_photo = $this->path . '/img/noimg.png';
            }

            $annonces[] = $a;
        }

        return $annonces;
    }
}
