<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablaLogNd extends Model
{
    public $connection = 'ibg_100_7';
    protected $table = "vp_tabla_log_nd";
    protected $primarykey = "td_consec";
    public $timestamps = false;
    protected $fillable = ['td_factura', 'td_estado', 'td_ruta_xml', 'td_nomb_xml', 'td_observ', 'td_cadv_clase', 'td_cufe', 'td_fec_nota', 'td_hora_nota', 'td_sucur', 'td_vlr_nota', 'td_cadv_numdoc', 'td_nuoren_nota','td_canc_numnot','td_canc_nitcli', 'td_dpto', 'td_codmuni', 'tabla_enlace', 'td_feccrea', 'td_usrcrea'];
}
