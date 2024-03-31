<?php

namespace controller\app\actions\post;

use controller\app\actions\AbstractAction;
use controller\app\model\ApiKey;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class KeyGeneratorAction extends AbstractAction
{

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $twig = Twig::fromRequest($request);
        $nom = $request->getParsedBody()['nom'];

        $nospace_nom = str_replace(' ', '', $nom);

        $menu = array(
            array('href' => $this->path,
                'text' => 'Acceuil'),
            array('href' => $this->path . "/search",
                'text' => "Recherche")
        );

        if ($nospace_nom === '') {
            return $twig->render(
                $response, "key_generator.html.twig",
                array(
                    "breadcrumb" => $menu,
                    "chemin" => $this->path,
                    "categories" => $this->categoryService->getCategories()
                )
            );
        } else {
            // Génere clé unique de 13 caractères
            $key = uniqid();
            // Ajouter clé dans la base
            $apikey = new ApiKey();

            $apikey->id_apikey = $key;
            $apikey->name_key = htmlentities($nom);
            $apikey->save();

            return $twig->render(
                $response, "key_generator-result.html.twig",
                array(
                    "breadcrumb" => $menu,
                    "chemin" => $this->path,
                    "categories" => $this->categoryService->getCategories(),
                    "key" => $key
                )
            );
        }
    }
}
