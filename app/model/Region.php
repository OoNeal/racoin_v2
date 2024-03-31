<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    protected $table = 'region';
    protected $primaryKey = 'id_region';
}

?>
