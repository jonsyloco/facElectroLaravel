<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fact_Detalle extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "fact_detalle";
    protected $primarykey = "deta_consec";
    public $timestamps = false;  
}
