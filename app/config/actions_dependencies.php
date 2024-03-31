<?php


use controller\app\actions\get\DepartmentAction;
use controller\app\actions\ObjetControlleur;
use controller\app\model\Categorie;
use controller\app\service\classes\CategoryService;

return [
    'menu' => [
        [
            'href' => dirname($_SERVER['SCRIPT_NAME']),
            'text' => 'Accueil'
        ]
    ],
    'path' => dirname($_SERVER['SCRIPT_NAME']),
    'category' => new Categorie(),
    'category_service' => new CategoryService(),
    'department_service' => $dpt = new DepartmentAction(),
    'object_service' => $obj = new ObjetControlleur(),
];
