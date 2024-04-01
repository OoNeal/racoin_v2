<?php

namespace controller\app\actions\get;

use controller\app\actions\AbstractAction;
use controller\app\model\Categorie;
use OpenApi\Annotations as OA;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApiCategoriesAction extends AbstractAction
{


    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Récupère la liste des catégories",
     *     tags={"Catégories"},
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
        $c = Categorie::get();
        $links = [];
        foreach ($c as $cat) {
            $links['self']['href'] = '/api/categorie/' . $cat->id_categorie;
            $cat->links = $links;
        }

        $this->logger->info("Categories");
        $response->getBody()->write(json_encode($c));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
