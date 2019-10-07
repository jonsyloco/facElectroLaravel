<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
     //
     public $connection = 'ibg_100_7';
     protected $table = "vp_fact";
     protected $primarykey = "ct_consec";
     public $timestamps = false;     
    //  protected $fillable = ['nombre', 'ciudad'];
   
 
    /**join con el detalle de la factura */
    public function fact_detalle() 
    {
        return $this->hasMany('App\Fact_Detalle','deta_fact_numdoc','fact_cadv_numdoc');
    }

    /**join con la compañia */
    public function compania(){
        return $this->hasOne('App\Compania','codigo','fact_compania');
    }

    
     
}
