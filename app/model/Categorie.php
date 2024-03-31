<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    public $timestamps = false;
    protected $table = 'categorie';
    protected $primaryKey = 'id_categorie';
}

?>
