<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota_Detalle extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_notadeb_detalle";
    protected $primarykey = "nota_consec";
    public $timestamps = false;
}
