<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $timestamps = false;
    protected $table = 'photo';
    protected $primaryKey = 'id_photo';

    public function annonce()
    {
        return $this->belongsTo('controller\app\model\Annonce', 'id_annonce');
    }
}

?>
