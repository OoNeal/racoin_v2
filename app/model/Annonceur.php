<?php

namespace controller\app\model;

class Annonceur extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'annonceur';
    protected $primaryKey = 'id_annonceur';
    public $timestamps = false;

    public function annonce()
    {
        return $this->hasMany('controller\app\model\Annonce', 'id_annonceur');
    }
}
