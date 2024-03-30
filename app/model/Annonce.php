<?php

namespace controller\app\model;

class Annonce extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'annonce';
    protected $primaryKey = 'id_annonce';
    public $timestamps = false;
    public $links = null;


    public function annonceur()
    {
        return $this->belongsTo('controller\app\model\Annonceur', 'id_annonceur');
    }

    public function photo()
    {
        return $this->hasMany('controller\app\model\Photo', 'id_photo');
    }

}
?>
