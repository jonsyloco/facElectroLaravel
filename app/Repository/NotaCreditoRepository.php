<?php
namespace App\Repository;

use App\Compania;
use App\NotaCredito;
use App\Sucursal;
use Illuminate\Support\Facades\DB;

class NotaCreditoRepository{


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de traer las notas que van hacer enviadas a la DIAN
     @return:  array-object
     */    
    public static function getNotasEnviar(){
       $notas = NotaCredito::join('companias','codigo','=',DB::raw('(nc_codcompania + 0)'))
       ->whereRaw('nc_feccrea >= today-10')
       ->whereNotNull('nc_cufe_fact')
       ->whereRaw("trim(nc_cufe_fact) <> ''")
       ->where('nc_consec','=','379')
       ->get();
    //    ->toSql(); //para ver le SQL
    
       return $notas;
    }

}