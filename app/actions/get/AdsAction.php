<?php

namespace controller\app\actions\get;

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

class AdsAction extends AbstractAction
{

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $twig = Twig::fromRequest($request);

        return $twig->render(
            $response, "ads.html.twig", [
            "breadcrumb" => $this->menu,
            "chemin" => $this->path,
            "categories" => $this->categoryService->getCategories(),
            "annonces" => $this->getAll()
            ]
        );
    }

    private function getAll(): array
    {
        $annonce = Annonce::with(['annonceur', 'photo'])
            ->orderBy('id_annonce', 'desc')
            ->take(12)
            ->get();
        return $annonce->map(
            function ($annonce) {
                $photo = Photo::where('id_annonce', '=', $annonce->id_annonce)->get();
                $annonce->nb_photo = $photo->count();
                $annonce->url_photo = $photo->first() ? $photo->first()->url_photo : '/img/noimg.png';
                $annonce->nom_annonceur = $annonce->annonceur->nom_annonceur;
                return $annonce;
            }
        )->toArray();
    }
}
