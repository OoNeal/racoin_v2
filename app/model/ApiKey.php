<?php

namespace controller\app\model;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    public $timestamps = false;
    protected $table = 'apikey';
    protected $primaryKey = 'id_key';

}

?>
