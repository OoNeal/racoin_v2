<?php


use controller\app\actions\get\DepartmentAction;
use controller\app\service\classes\CategoryService;
use controller\app\service\classes\ItemService;

return [
    'menu' => [
        [
            'href' => dirname($_SERVER['SCRIPT_NAME']),
            'text' => 'Accueil'
        ]
    ],
    'path' => dirname($_SERVER['SCRIPT_NAME']),
    'category_service' => new CategoryService,
    'department_service' => new DepartmentAction,
    'item_service' => new ItemService
];
