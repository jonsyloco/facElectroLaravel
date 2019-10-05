<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{

    public $connection = 'ibg_100_7';
    protected $table = "nota_credito";
    protected $primarykey = "nc_consec";
    public $timestamps = false;

    /**join con la compañia */
    public function compania()
    {
        return $this->hasOne('App\Compania', 'codigo', 'nc_codcompania');
    }
}
