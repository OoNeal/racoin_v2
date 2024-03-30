<?php

use controller\app\controller\getCategorie;
use controller\app\controller\getDepartment;
use controller\app\controller\index;
use controller\app\controller\KeyGenerator;
use controller\app\controller\ObjetControlleur;
use controller\app\controller\Search;
use controller\app\controller\viewAnnonceur;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $objetControlleur = new ObjetControlleur();
    $cat = new getCategorie();
    $dpt = new getDepartment();
    $responseFactory = AppFactory::determineResponseFactory();

    $app->add(
        function (Request $request, RequestHandlerInterface $handler) use ($responseFactory) {
            $uri = $request->getUri();
            $path = $uri->getPath();
            if ($path != '/' && str_ends_with($path, '/')) {
                $uri = $uri->withPath(substr($path, 0, -1));
                if ($request->getMethod() == 'GET') {
                    $response = $responseFactory->createResponse(302);
                    return $response->withHeader('Location', (string)$uri);
                } else {
                    return $handler->handle($request->withUri($uri));
                }
            }
            return $handler->handle($request);
        }
    );

    if (!isset($_SESSION)) {
        session_start();
        $_SESSION['formStarted'] = true;
    }

    if (!isset($_SESSION['token'])) {
        $token = md5(uniqid(rand(), true));
        $_SESSION['token'] = $token;
        $_SESSION['token_time'] = time();
    }

    $menu = [
        [
            'href' => './index.php',
            'text' => 'Accueil'
        ]
    ];

    $chemin = dirname($_SERVER['SCRIPT_NAME']);

    $app->get(
        '/', function (Request $request, Response $response) use ($cat, $twig, $menu, $chemin) {
            $index = new Index();
            return $index->displayAllAnnonce($twig, $menu, $chemin, $cat->getCategories());
        }
    );

    $app->get(
        '/item/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
            $n = $arg['n'];
            $item = new ObjetControlleur();
            return $item->afficherObjet($twig, $menu, $chemin, $n, $cat->getCategories());
        }
    );

    $app->get(
        '/add', function () use ($twig, $app, $menu, $chemin, $cat, $dpt) {
            $ajout = new ObjetControlleur();
            return $ajout->ajouterObjetVue($twig, $menu, $chemin, $cat->getCategories(), $dpt->getAllDepartments());
        }
    );

    $app->post(
        '/add', function ($request) use ($twig, $app, $menu, $chemin) {
            $allPostVars = $request->getParsedBody();
            $ajout = new ObjetControlleur();
            return $ajout->ajouterNouvelObjet($twig, $menu, $chemin, $allPostVars);
        }
    );

    $app->get(
        '/item/{id}/edit', function ($request, $response, $arg) use ($twig, $menu, $chemin, $objetControlleur) {
            $id = $arg['id'];
            return $objetControlleur->modifierObjetGet($twig, $menu, $chemin, $id);
        }
    );
    $app->post(
        '/item/{id}/edit', function ($request, $response, $arg) use ($twig, $app, $menu, $chemin, $cat, $dpt, $objetControlleur) {
            $id = $arg['id'];
            $allPostVars = $request->getParsedBody();
            return $objetControlleur->modifierObjetPost($twig, $menu, $chemin, $id, $allPostVars, $cat->getCategories(), $dpt->getAllDepartments());
        }
    );

    $app->map(
        ['GET, POST'], '/item/{id}/confirm', function ($request, $response, $arg) use ($twig, $app, $menu, $chemin, $objetControlleur) {
            $id = $arg['id'];
            $allPostVars = $request->getParsedBody();
            return $objetControlleur->confirmerModification($twig, $menu, $chemin, $id, $allPostVars);
        }
    );

    $app->get(
        '/search', function () use ($twig, $menu, $chemin, $cat) {
            $s = new Search();
            return $s->show($twig, $menu, $chemin, $cat->getCategories());
        }
    );


    $app->post(
        '/search', function ($request, $response) use ($app, $twig, $menu, $chemin, $cat) {
            $array = $request->getParsedBody();
            $s = new Search();
            return $s->research($array, $twig, $menu, $chemin, $cat->getCategories());
        }
    );

    $app->get(
        '/annonceur/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
            $n = $arg['n'];
            $annonceur = new viewAnnonceur();
            return $annonceur->afficherAnnonceur($twig, $menu, $chemin, $n, $cat->getCategories());
        }
    );

    $app->get(
        '/del/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin) {
            $n = $arg['n'];
            $item = new ObjetControlleur();
            return $item->supprimerObjetGet($twig, $menu, $chemin, $n);
        }
    );

    $app->post(
        '/del/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
            $n = $arg['n'];
            $item = new ObjetControlleur();
            return $item->supprimerObjetPost($twig, $menu, $chemin, $n, $cat->getCategories());
        }
    );

    $app->get(
        '/cat/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
            $n = $arg['n'];
            $categorie = new \controller\app\controller\getCategorie();
            return $categorie->displayCategorie($twig, $menu, $chemin, $cat->getCategories(), $n);
        }
    );

    $app->get(
        '/api[/]', function () use ($twig, $menu, $chemin, $cat) {
            $template = $twig->load('api.html.twig');
            $menu = array(
            array(
                'href' => $chemin,
                'text' => 'Acceuil'
            ),
            array(
                'href' => $chemin . '/api',
                'text' => 'Api'
            )
            );
            $html = $template->render(array('breadcrumb' => $menu, 'chemin' => $chemin));

            $response = new Response();
            $response->getBody()->write($html);

            return $response;
        }
    );

    $app->group(
        '/api', function (RouteCollectorProxy $group) use ($cat, $chemin, $menu, $twig) {

            $group->group(
                '/annonce', function (RouteCollectorProxy $group) {

                    $group->get(
                        '/{id}', function ($request, $response, $args) {
                            $id = $args['id'];
                            $annonceList = ['id_annonce', 'id_categorie as categorie', 'id_annonceur as annonceur', 'id_departement as departement', 'prix', 'date', 'titre', 'description', 'ville'];
                            $return = Annonce::select($annonceList)->find($id);

                            if (isset($return)) {
                                $return->categorie = Categorie::find($return->categorie);
                                $return->annonceur = Annonceur::select('email', 'nom_annonceur', 'telephone')->find($return->annonceur);
                                $return->departement = Departement::select('id_departement', 'nom_departement')->find($return->departement);
                                $links = [];
                                $links['self']['href'] = '/api/annonce/' . $return->id_annonce;
                                $return->links = $links;
                                return $response->withJson($return);
                            } else {
                                throw new HttpNotFoundException($request);
                            }
                        }
                    );

                }
            );

            $group->group(
                '/annonces', function (RouteCollectorProxy $group) {

                    $group->get(
                        '', function ($request, $response) {
                            $annonceList = ['id_annonce', 'prix', 'titre', 'ville'];
                            $response->getBody()->write(Annonce::all($annonceList)->toJson());
                            return $response->withHeader('Content-Type', 'application/json');
                        }
                    );

                }
            );

            $group->group(
                '/categorie', function (RouteCollectorProxy $group) {

                    $group->get(
                        '/{id}', function ($request, $response, $args) {
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
                            return $response->withJson($c);
                        }
                    );

                }
            );

            $group->group(
                '/categories', function (RouteCollectorProxy $group) {

                    $group->get(
                        '', function ($request, $response) {
                            $c = Categorie::get();
                            $links = [];
                            foreach ($c as $cat) {
                                $links['self']['href'] = '/api/categorie/' . $cat->id_categorie;
                                $cat->links = $links;
                            }
                            $links['self']['href'] = '/api/categories/';
                            return $response->withJson($c);
                        }
                    );

                }
            );

            $group->get(
                '/key', function ($request, $response) use ($twig, $menu, $chemin, $cat) {
                    $kg = new KeyGenerator();
                    return $kg->show($twig, $menu, $chemin, $cat->getCategories());
                }
            );

            $group->post(
                '/key', function ($request, $response) use ($twig, $menu, $chemin, $cat) {
                    $parsedBody = $request->getParsedBody();
                    $nom = $parsedBody['nom'];

                    $kg = new KeyGenerator();
                    return $kg->generateKey($twig, $menu, $chemin, $cat->getCategories(), $nom);
                }
            );

        }
    );
};
