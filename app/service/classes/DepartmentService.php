<?php

namespace controller\app\service\classes;

use controller\app\model\Departement;
use controller\app\service\interfaces\DepartmentInterface;

class DepartmentService implements DepartmentInterface

{
    /**
     * @return mixed
     */
    public function getDepartments(): mixed
    {
        return Departement::orderBy('nom_departement')->get()->toArray();
    }
}
