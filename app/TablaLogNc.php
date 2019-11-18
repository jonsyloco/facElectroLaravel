<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablaLogNc extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_tabla_log_nc";
    protected $primarykey = "tn_consec";
    public $timestamps = false;
    protected $fillable = ['tn_estado','tn_rutaxml','tn_nombxml','tn_observ','tn_consec_fact','tn_cufe','tn_fecnot','tn_horanot','tn_sucur','tn_vlrtotal','tn_mc_numnot','tn_mc_nitcli','tn_codmuni','tn_coddpto','tn_usrcrea','tn_feccrea','tn_mc_consec'];
     
}
