<?php

namespace controller\app\controller;

use controller\app\model\Annonce;
use controller\app\model\Photo;
use controller\app\model\Annonceur;
use Slim\Psr7\Response;

class index
{
    protected $annonce = array();

    public function displayAllAnnonce($twig, $menu, $chemin, $cat)
    {
        $template = $twig->load("index.html.twig");
        $menu     = array(
            array(
                'href' => $chemin,
                'text' => 'Accueil'
            ),
        );

        $this->getAll($chemin);
        $html = $template->render(array(
            "breadcrumb" => $menu,
            "chemin"     => $chemin,
            "categories" => $cat,
            "annonces"   => $this->annonce
        ));

        $response = new Response();
        $response->getBody()->write($html);

        return $response;
    }

    public function getAll($chemin)
    {
        $tmp     = Annonce::with("Annonceur")->orderBy('id_annonce', 'desc')->take(12)->get();
        $annonce = [];
        foreach ($tmp as $t) {
            $t->nb_photo = Photo::where("id_annonce", "=", $t->id_annonce)->count();
            if ($t->nb_photo > 0) {
                $t->url_photo = Photo::select("url_photo")
                    ->where("id_annonce", "=", $t->id_annonce)
                    ->first()->url_photo;
            } else {
                $t->url_photo = '/img/noimg.png';
            }
            $t->nom_annonceur = Annonceur::select("nom_annonceur")
                ->where("id_annonceur", "=", $t->id_annonceur)
                ->first()->nom_annonceur;
            array_push($annonce, $t);
        }
        $this->annonce = $annonce;
    }
}
