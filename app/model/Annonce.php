<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    public $timestamps = false;
    public $links = null;
    protected $table = 'annonce';
    protected $primaryKey = 'id_annonce';

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
