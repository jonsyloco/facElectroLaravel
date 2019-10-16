<?php

namespace App\Repository;

use App\Compania;
use App\Fact;
use App\Sucursal;
use App\TablaLogCadv;
use Carbon\Carbon;

class FactRepository
{


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 26/09/2019
     @Descripcion: Metodo encargado de traer las facturas para ser enviadas a la DIAN
     @return:  
     */
    public static function getFacturas_()
    {
        // return (Fact::where('fact_cadv_numdoc','11079669')->get());
        $facturas = Fact::on('ibg_100_7')
            ->join('vp_fact_detalle', 'fact_cadv_numdoc', '=', 'deta_fact_numdoc')
            ->whereRaw("to_char(fact_feccrea,'%m%d%Y') ='09262019'")
            ->select('*')
            ->get();

        return $facturas;
    }

    public static function getFacturas()
    {
        // return (Fact::where('fact_cadv_numdoc','11079669')->get());
        $facturas = Fact::on('ibg_100_7')
            ->whereRaw("to_char(fact_feccrea,'%m%d%Y') ='09262019'")
            // ->where('fact_cadv_numdoc', '=', '61021397') //factura credito
            // ->limit(14)
            ->get();

        return $facturas;
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 11/10/2019
     @Descripcion: Metodo encargado de guardar en la tabla de log
     el resultado de haber enviado la factura a la DIAN
     @return:  
     */
    public static function insertTablaLogCadv($num_factura,$observ,$cadv_fact,$cufe,$fec_fact,$hora_fact,$sucur,$val_fact,$consec_fact_cadv,$nitcli,$coddpto,$codmuni,$tabla_enlace,$trackid,$estado="P",$ruta_xml="",$nomb_xml="")
    { 

        $fechaHoy = Carbon::now();
        
        
        $log = new TablaLogCadv();
        $log->factura=$num_factura;
        $log->estado='P';
        $log->ruta_xml='por definir la ruta';
        $log->nomb_xml='por definir el nombre';
        $log->observ=$observ;
        $log->cadv_fact=$cadv_fact;
        $log->cufe=$cufe;
        $log->fec_fact=$fec_fact;
        $log->hora_fact=$fechaHoy->toTimeString();
        $log->sucur=$sucur;
        $log->val_fact=$val_fact;
        $log->consec_fact_cadv=$consec_fact_cadv;
        $log->nitcli=$nitcli;
        $log->coddpto=$coddpto;
        $log->codmuni=$codmuni;
        $log->trackid=$trackid;
        $log->tabla_enlace=$tabla_enlace;
        $log->feccrea=$fechaHoy->format('m/d/Y');
        $log->usrcrea='SYSTEMA';
        $log->horacre=$fechaHoy->toTimeString();
        return $log->save();
    }
}
