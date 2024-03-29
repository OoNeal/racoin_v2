<?php

namespace controller\app\controller;

use controller\app\model\ApiKey;
use Slim\Psr7\Response;

class KeyGenerator {

    function show($twig, $menu, $chemin, $cat) {
        $template = $twig->load("key-generator.html.twig");
        $menu = array(
            array('href' => $chemin,
                'text' => 'Acceuil'),
            array('href' => $chemin."/search",
                'text' => "Recherche")
        );
        $html = $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat));

        $response = new Response();
        $response->getBody()->write($html);

        return $response;
    }

    function generateKey($twig, $menu, $chemin, $cat, $nom) {
        $nospace_nom = str_replace(' ', '', $nom);

        if($nospace_nom === '') {
            $template = $twig->load("key-generator-error.html.twig");
            $menu = array(
                array('href' => $chemin,
                    'text' => 'Acceuil'),
                array('href' => $chemin."/search",
                    'text' => "Recherche")
            );

            $html = $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat));

            $response = new Response();
            $response->getBody()->write($html);

            return $response;
        } else {
            $template = $twig->load("key-generator-result.html.twig");
            $menu = array(
                array('href' => $chemin,
                    'text' => 'Acceuil'),
                array('href' => $chemin."/search",
                    'text' => "Recherche")
            );

            // Génere clé unique de 13 caractères
            $key = uniqid();
            // Ajouter clé dans la base
            $apikey = new ApiKey();

            $apikey->id_apikey = $key;
            $apikey->name_key = htmlentities($nom);
            $apikey->save();

            $html = $template->render(array("breadcrumb" => $menu, "chemin" => $chemin, "categories" => $cat, "key" => $key));

            $response = new Response();
            $response->getBody()->write($html);

            return $response;
        }

    }

}

?>
