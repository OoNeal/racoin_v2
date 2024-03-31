<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    public $timestamps = false;
    protected $table = 'sous_categorie';
    protected $primaryKey = 'id_sous_categorie';
}

?>
