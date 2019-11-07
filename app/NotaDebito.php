<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaDebito extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_nota_debito";
    protected $primarykey = "nd_consec";
    public $timestamps = false;

    
}
