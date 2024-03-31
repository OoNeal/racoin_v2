<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Categorie;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApiCategoryAction extends AbstractAction
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
        $a = Annonce::select('id_annonce', 'prix', 'titre', 'ville')->where('id_categorie', '=', $id)->get();
        $links = [];

        foreach ($a as $ann) {
            $links['self']['href'] = '/api/annonce/' . $ann->id_annonce;
            $ann->links = $links;
        }

        $c = Categorie::find($id);
        $links['self']['href'] = '/api/categorie/' . $id;
        $c->links = $links;
        $c->annonces = $a;
        $response->getBody()->write(json_encode($c));
        return $response->withHeader('Content-Type', 'application/json');
    }
}