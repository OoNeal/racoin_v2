<?php

namespace controller\app\actions\get;

use controller\app\model\Departement;

class DepartmentAction
{

    protected $departments = array();

    public function getDepartments()
    {
        return Departement::orderBy('nom_departement')->get()->toArray();
    }
}
