<?php

namespace controller\app\service\classes;

use controller\app\model\Categorie;
use controller\app\service\interfaces\CategoryInterface;

class CategoryService implements CategoryInterface
{
    /**
     * @return mixed
     */
    public function getCategories(): mixed
    {
        return Categorie::orderBy('nom_categorie')->get()->toArray();
    }
}