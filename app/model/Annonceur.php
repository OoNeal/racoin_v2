<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Annonceur extends Model
{
    public $timestamps = false;
    protected $table = 'annonceur';
    protected $primaryKey = 'id_annonceur';

    public function annonce()
    {
        return $this->hasMany('controller\app\model\Annonce', 'id_annonceur');
    }
}
