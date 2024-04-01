<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Annonce;
use OpenApi\Annotations as OA;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApiAnnoncesAction extends AbstractAction
{

    /**
     * @OA\Get(
     *     path="/api/annonces",
     *     summary="Récupère la liste des annonces",
     *     tags={"Annonces"},
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
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
        $annonceList = ['id_annonce', 'prix', 'titre', 'ville'];

        $this->logger->info("Annonces");
        $response->getBody()->write(json_encode(Annonce::all($annonceList)));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
