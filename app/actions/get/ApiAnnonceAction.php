<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use OpenApi\Annotations as OA;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


class ApiAnnonceAction extends AbstractAction
{

    /**
     * @OA\Get(
     *     path="/api/annonce/{id}",
     *     summary="Récupère les détails d'une annonce spécifique",
     *     tags={"Annonces"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="L'identifiant de l'annonce à récupérer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Annonce non trouvée"
     *     )
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

        $this->logger->info("Annonce id: $id");
        $response->getBody()->write(json_encode($return));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
