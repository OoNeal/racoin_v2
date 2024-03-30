<?php

namespace controller\app\model;

class Photo extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'photo';
    protected $primaryKey = 'id_photo';
    public $timestamps = false;

    public function annonce()
    {
        return $this->belongsTo('controller\app\model\Annonce', 'id_annonce');
    }
}

?>
