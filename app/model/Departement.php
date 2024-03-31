<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    public $timestamps = false;
    protected $table = 'departement';
    protected $primaryKey = 'id_departement';
}

?>
