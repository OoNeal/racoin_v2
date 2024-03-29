<?php

namespace controller\app\controller;

use AllowDynamicProperties;
use controller\app\model\Annonce;
use controller\app\model\Annonceur;
use controller\app\model\Categorie;
use controller\app\model\Departement;
use controller\app\model\Photo;

#[AllowDynamicProperties] class ObjetControlleur
{

    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $n
     * @param $cat
     */
    function afficherObjet($twig, $menu, $chemin, $n, $cat)
    {

        $this->annonce = Annonce::find($n);
        if (!isset($this->annonce)) {
            return "404";
        }

        $menu = array(
            array('href' => $chemin,
                'text' => 'Accueil'),
            array('href' => $chemin . "/cat/" . $n,
                'text' => Categorie::find($this->annonce->id_categorie)?->nom_categorie),
            array('href' => $chemin . "/item/" . $n,
                'text' => $this->annonce->titre)
        );

        $this->annonceur = Annonceur::find($this->annonce->id_annonceur);
        $this->departement = Departement::find($this->annonce->id_departement);
        $this->photo = Photo::where('id_annonce', '=', $n)->get();
        $template = $twig->load("item.html.twig");
        return $template->render(
            array(
                "breadcrumb" => $menu,
                "chemin" => $chemin,
                "annonce" => $this->annonce,
                "annonceur" => $this->annonceur,
                "dep" => $this->departement->nom_departement,
                "photo" => $this->photo,
                "categories" => $cat
            ));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $cat
     * @param $dpt
     */
    function ajouterObjetVue($twig, $menu, $chemin, $cat, $dpt)
    {
        $template = $twig->load("add.html.twig");
        return $template->render(
            array(
                "breadcrumb" => $menu,
                "chemin" => $chemin,
                "categories" => $cat,
                "departements" => $dpt
            ));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $allPostVars
     */
    function ajouterNouvelObjet($twig, $menu, $chemin, $allPostVars)
    {

        $array = $this->formatterTableau($allPostVars);
        $errors = $this->estChampVide($array);

        // S'il y a des erreurs on redirige vers la page d'erreur
        if (!empty($errors)) {
            $this->afficherErreurs($twig, $menu, $chemin, $errors);
        } // sinon on ajoute à la base et on redirige vers une page de succès
        else {
            $annonce = new Annonce();
            $annonceur = new Annonceur();

            $this->CreerHtmlEntites($array, $annonce);
            $annonceur->save();
            $annonceur->annonce()->save($annonce);

            $template = $twig->load("add-confirm.html.twig");
            return $template->render(array("breadcrumb" => $menu, "chemin" => $chemin));
        }
    }

    /**
     * @param $array
     * @return array
     */
    private function formatterTableau($array): array
    {
        /*
        * On récupère tous les champs du formulaire en supprimant
        * les caractères invisibles en début et fin de chaîne.
        */
        return array_map('trim', $array);
    }

    /**
     * @param $array
     * @return array
     */
    private function estChampVide($array): array
    {
        $errors = [];
        foreach ($array as $key => $value) {
            if (empty($value)) {
                $errors[$key] = 'Veuillez remplir le champ ' . $key;
            }
            if (in_array($key, ['phone', 'departement', 'categorie', 'price'])) {
                if (!is_numeric($value)) {
                    $errors[$key] = 'Veuillez remplir le champ ' . $key;
                }
            }
            if ($key == 'email') {
                if (!$this->estEmail($value)) {
                    $errors[$key] = 'Veuillez entrer une adresse mail correcte';
                }
            }
        }
        // On vire les cases vides
        return array_values(array_filter($errors));
    }

    /**
     * @param string $email
     * @return false|int
     */
    private function estEmail(string $email): false|int
    {
        return (preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $errors
     * @return void
     */
    private function afficherErreurs($twig, $menu, $chemin, $errors)
    {
        $template = $twig->load("add-error.html.twig");
        return $template->render(array(
                "breadcrumb" => $menu,
                "chemin" => $chemin,
                "errors" => $errors)
        );
    }

    /**
     * @param array $array
     * @param Annonce $annonce
     * @return Annonce
     */
    private function CreerHtmlEntites(array $array, Annonce $annonce): Annonce
    {
        foreach ($array as $key => $value) {
            $annonce->$key = htmlentities($value);
        }
        return $annonce;
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $n
     */
    function supprimerObjetGet($twig, $menu, $chemin, $n)
    {
        $this->annonce = Annonce::find($n);
        if (!isset($this->annonce)) {
            echo "404";
            return;
        }
        $template = $twig->load("delGet.html.twig");
        return $template->render(array("breadcrumb" => $menu,
            "chemin" => $chemin,
            "annonce" => $this->annonce));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $n
     * @param $cat
     */
    function supprimerObjetPost($twig, $menu, $chemin, $n, $cat)
    {
        $this->annonce = Annonce::find($n);
        $reponse = false;
        if (password_verify($_POST["pass"], $this->annonce->mdp)) {
            $reponse = true;
            photo::where('id_annonce', '=', $n)->delete();
            $this->annonce->delete();
        }

        $template = $twig->load("delPost.html.twig");
        return $template->render(array("breadcrumb" => $menu,
            "chemin" => $chemin,
            "annonce" => $this->annonce,
            "pass" => $reponse,
            "categories" => $cat));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $id
     */
    function modifierObjetGet($twig, $menu, $chemin, $id)
    {
        $this->annonce = Annonce::find($id);
        if (!isset($this->annonce)) {
            return "404";
        }
        $template = $twig->load("modifyGet.html.twig");
        return $template->render(array("breadcrumb" => $menu,
            "chemin" => $chemin,
            "annonce" => $this->annonce));
    }

    /**
     * @param $twig
     * @param $menu
     * @param $chemin
     * @param $n
     * @param $cat
     * @param $dpt
     * */
    function modifierObjetPost($twig, $menu, $chemin, $n, $cat, $dpt)
    {
        $this->annonce = Annonce::find($n);
        $this->annonceur = Annonceur::find($this->annonce->id_annonceur);
        $this->categItem = Categorie::find($this->annonce->id_categorie)->nom_categorie;
        $this->dptItem = Departement::find($this->annonce->id_departement)->nom_departement;

        $reponse = false;
        var_dump($_POST["pass"]);
        if (password_verify($_POST["pass"], $this->annonce->mdp)) {
            $reponse = true;
        }

        $template = $twig->load("modifyPost.html.twig");
        return $template->render(array("breadcrumb" => $menu,
            "chemin" => $chemin,
            "annonce" => $this->annonce,
            "annonceur" => $this->annonceur,
            "pass" => $reponse,
            "categories" => $cat,
            "departements" => $dpt,
            "dptItem" => $this->dptItem,
            "categItem" => $this->categItem));
    }

    function confirmerModification($twig, $menu, $chemin, $allPostVars, $id)
    {
        $array = $this->formatterTableau($allPostVars);
        $errors = $this->estChampVide($array);

        // S'il y a des erreurs on redirige vers la page d'erreur
        if (!empty($errors)) {
            $this->afficherErreurs($twig, $menu, $chemin, $errors);
        } // sinon on ajoute à la base et on redirige vers une page de succès
        else {
            $this->annonce = Annonce::find($id);
            $idAnnonceur = $this->annonce->id_annonceur;
            $this->annonceur = Annonceur::find($idAnnonceur);

            $this->annonce = $this->CreerHtmlEntites($array, $this->annonce);
            $this->annonceur->save();
            $this->annonceur->annonce()->save($this->annonce);


            $template = $twig->load("modif-confirm.html.twig");
            return $template->render(array("breadcrumb" => $menu, "chemin" => $chemin));
        }
    }
}
