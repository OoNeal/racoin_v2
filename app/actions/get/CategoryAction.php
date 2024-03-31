<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Photo;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CategoryAction extends AbstractAction
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
            array('href' => $this->path . "/cat/" . $args['id'],
                'text' => Categorie::find($args['id'])->nom_categorie)
        );

        $annonce = $this->getCategorieContent($this->path, $args['id']);
        return $twig->render(
            $response, "ads.html.twig",
            array(
                "breadcrumb" => $menu,
                "chemin" => $this->path,
                "categories" => $this->categoryService->getCategories(),
                "annonces" => $annonce)
        );
    }

    public function getCategorieContent($chemin, $n)
    {
        $tmp = Annonce::with("Annonceur")->orderBy('id_annonce', 'desc')->where('id_categorie', "=", $n)->get();
        $annonce = [];
        foreach ($tmp as $t) {
            $t->nb_photo = Photo::where("id_annonce", "=", $t->id_annonce)->count();
            if ($t->nb_photo > 0) {
                $t->url_photo = Photo::select("url_photo")
                    ->where("id_annonce", "=", $t->id_annonce)
                    ->first()->url_photo;
            } else {
                $t->url_photo = $chemin . '/img/noimg.png';
            }
            $t->nom_annonceur = Annonceur::select("nom_annonceur")
                ->where("id_annonceur", "=", $t->id_annonceur)
                ->first()->nom_annonceur;
            $annonce[] = $t;
        }
        return $annonce;
    }
}
