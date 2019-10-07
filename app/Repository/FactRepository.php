<?php

namespace App\Repository;

use App\Compania;
use App\Fact;
use App\Sucursal;

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
            ->limit(14)
            ->get();

        return $facturas;
    }
}
