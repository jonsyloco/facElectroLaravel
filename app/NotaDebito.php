<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaDebito extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_nota_debito";
    protected $primarykey = "nd_consec";
    public $timestamps = false;

    /**join con el detalle de la factura */
    public function nota_detalle()
    {
        return $this->hasMany('App\Nota_Detalle', 'nota_cacc_numnot', 'nd_canc_numnot');
    }

    /**join con la compañia */
    public function compania()
    {
        return $this->hasOne('App\Compania', 'codigo', 'nd_codcompania');
    }
}
