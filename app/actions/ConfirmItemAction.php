<?php

namespace controller\app\actions;

use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ConfirmItemAction extends AbstractAction
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $array = $this->objectService->formatterTableau($request->getParsedBody());
        $errors = $this->objectService->estChampVide($array);
        $twig = Twig::fromRequest($request);

        // S'il y a des erreurs on redirige vers la page d'erreur
        if (!empty($errors)) {
            return $twig->render($response, "add-error.html.twig",
                array(
                    "breadcrumb" => $this->menu,
                    "chemin" => $this->path,
                    "errors" => $errors)
            );
        }

        // sinon on ajoute à la base et on redirige vers une page de succès
        $annonce = Annonce::find($args['id']);
        $idAnnonceur = $annonce->id_annonceur;
        $annonceur = Annonceur::find($idAnnonceur);

        $annonce = $this->objectService->CreerHtmlEntites($array, $annonce);
        $annonceur->save();
        $annonceur->annonce()->save($annonce);
        return $twig->render($response, "modif-confirm.html.twig",
            array(
                "breadcrumb" => $this->menu,
                "chemin" => $this->path)
        );
    }
}