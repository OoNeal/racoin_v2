<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Categorie;
use OpenApi\Annotations as OA;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApiCategoryAction extends AbstractAction
{


    /**
     * @OA\Get(
     *     path="/api/categorie/{id}",
     *     summary="Récupère les détails d'une catégorie spécifique",
     *     tags={"Catégories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="L'identifiant de la catégorie à récupérer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *     )
     * )
     * )
     */
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|Message
     */
    public function __invoke(Request $request, Response $response, array $args): Response|Message
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

        $this->logger->info("Category id: $id");
        $response->getBody()->write(json_encode($c));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
