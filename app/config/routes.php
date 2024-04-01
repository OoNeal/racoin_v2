<?php

use controller\app\actions\ConfirmItemAction;
use controller\app\actions\get\AddItemAction as AddItemActionGet;
use controller\app\actions\get\AdsAction;
use controller\app\actions\get\AdvertiserAction;
use controller\app\actions\get\ApiAnnonceAction;
use controller\app\actions\get\ApiAnnoncesAction;
use controller\app\actions\get\ApiCategoriesAction;
use controller\app\actions\get\ApiCategoryAction;
use controller\app\actions\get\ApiHomeAction;
use controller\app\actions\get\CategoryAction;
use controller\app\actions\get\ItemAction;
use controller\app\actions\get\KeyGeneratorAction as KeyGeneratorActionGet;
use controller\app\actions\get\ModifyItemAction as ModifyItemActionGet;
use controller\app\actions\get\SearchAction as SearchActionGet;
use controller\app\actions\post\AddItemAction as AddItemActionPost;
use controller\app\actions\post\DeleteItemAction as DeleteItemGet;
use controller\app\actions\post\DeleteItemAction as DeleteItemPost;
use controller\app\actions\post\KeyGeneratorAction as KeyGeneratorActionPost;
use controller\app\actions\post\ModifyItemAction as ModifyItemActionPost;
use controller\app\actions\post\SearchAction as SearchActionPost;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {

    $app->add(
        function (Request $request, RequestHandlerInterface $handler) {
            $responseFactory = AppFactory::determineResponseFactory();
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

    $app->get('/', AdsAction::class);

    $app->get('/item/{id}', ItemAction::class);

    $app->get('/add', AddItemActionGet::class);

    $app->post('/add', AddItemActionPost::class);

    $app->get('/item/{id}/edit', ModifyItemActionGet::class);

    $app->post('/item/{id}/edit', ModifyItemActionPost::class);

    $app->map(['GET, POST'], '/item/{id}/confirm', ConfirmItemAction::class);

    $app->get('/search', SearchActionGet::class);

    $app->post('/search', SearchActionPost::class);

    $app->get('/annonceur/{id}', AdvertiserAction::class);

    $app->get('/del/{id}', DeleteItemGet::class);

    $app->post('/del/{id}', DeleteItemPost::class);

    $app->get('/cat/{id}', CategoryAction::class);

    $app->get('/api[/]', ApiHomeAction::class);

    $app->group(
        '/api', function (RouteCollectorProxy $group) {

            $group->group(
                '/annonce', function (RouteCollectorProxy $group) {
                    $group->get('/{id}', ApiAnnonceAction::class);
                }
            );

            $group->group(
                '/annonces', function (RouteCollectorProxy $group) {
                    $group->get('', ApiAnnoncesAction::class);
                }
            );

            $group->group(
                '/categorie', function (RouteCollectorProxy $group) {
                    $group->get('/{id}', ApiCategoryAction::class);
                }
            );

            $group->group(
                '/categories', function (RouteCollectorProxy $group) {
                    $group->get('', ApiCategoriesAction::class);
                }
            );

            $group->get('/key', KeyGeneratorActionGet::class);

            $group->post('/key', KeyGeneratorActionPost::class);
        }
    );
};
