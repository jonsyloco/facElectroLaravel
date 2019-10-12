<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablaLogCadv extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_tabla_log_cadv";
    protected $primarykey = "consec";
    public $timestamps = false;  
    protected $fillable = ['factura','estado','ruta_xml','nomb_xml','observ','cadv_fact','cufe','fec_fact','hora_fact','sucur','val_fact','consec_fact_cadv','nitcli','coddpto','codmuni','tabla_enlace','feccrea','usrcrea','horacre'];
    



}
