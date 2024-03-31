<?php

namespace controller\app\service\classes;

use controller\app\model\Categorie;
use controller\app\service\interfaces\CategoryInterface;

class CategoryService implements CategoryInterface
{
    public function getCategories()
    {
        return Categorie::orderBy('nom_categorie')->get()->toArray();
    }
}