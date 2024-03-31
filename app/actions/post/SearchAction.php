<?php

namespace controller\app\actions\post;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class SearchAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $twig = Twig::fromRequest($request);
        $array = $request->getParsedBody();
        $menu = array(
            $this->menu,
            array('href' => $this->path . "/search",
                'text' => "Résultats de la recherche")
        );

        $query = Annonce::select();

        if (empty($array['motclef']) && empty($array['codepostal']) 
            && ($array['categorie'] === "Toutes catégories" || $array['categorie'] === "-----") 
            && ($array['prix-min'] === "Min") 
            && ($array['prix-max'] === "Max" || $array['prix-max'] === "nolimit")
        ) {
            $annonce = Annonce::all();
        } else {
            if (!empty($array['motclef'])) {
                $query->where('description', 'like', '%' . $array['motclef'] . '%');
            }

            if (!empty($array['codepostal'])) {
                $query->where('ville', '=', $array['codepostal']);
            }

            if ($array['categorie'] !== "Toutes catégories" && $array['categorie'] !== "-----") {
                $query->where('id_categorie', '=', $array['categorie']);
            }

            if ($array['prix-min'] !== "Min" && $array['prix-max'] !== "Max") {
                if ($array['prix-max'] !== "nolimit") {
                    $query->whereBetween('prix', array($array['prix-min'], $array['prix-max']));
                } else {
                    $query->where('prix', '>=', $array['prix-min']);
                }
            } elseif ($array['prix-max'] !== "Max" && $array['prix-max'] !== "nolimit") {
                $query->where('prix', '<=', $array['prix-max']);
            } elseif ($array['prix-min'] !== "Min") {
                $query->where('prix', '>=', $array['prix-min']);
            }

            $annonce = $query->get();
        }

        return $twig->render(
            $response, "ads.html.twig", [
            "breadcrumb" => $menu,
            "chemin" => $this->path,
            "categories" => $this->categoryService->getCategories(),
            "annonces" => $annonce
            ]
        );
    }
}
