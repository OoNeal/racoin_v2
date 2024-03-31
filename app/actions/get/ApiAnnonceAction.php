<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApiAnnonceAction extends AbstractAction
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|Message
     */
    public function __invoke(Request $request, Response $response, array $args): Response|\Slim\Psr7\Message
    {
        $id = $args['id'];
        $annonceList = ['id_annonce', 'id_categorie as categorie', 'id_annonceur as annonceur', 'id_departement as departement', 'prix', 'date', 'titre', 'description', 'ville'];
        $return = Annonce::select($annonceList)->find($id);

        if (!isset($return)) {
            throw new HttpNotFoundException($request);
        }
        $return->categorie = Categorie::find($return->categorie);
        $return->annonceur = Annonceur::select('email', 'nom_annonceur', 'telephone')->find($return->annonceur);
        $return->departement = Departement::select('id_departement', 'nom_departement')->find($return->departement);
        $links = [];
        $links['self']['href'] = '/api/annonce/' . $return->id_annonce;
        $return->links = $links;
        $response->getBody()->write(json_encode($return));
        return $response->withHeader('Content-Type', 'application/json');
    }
}