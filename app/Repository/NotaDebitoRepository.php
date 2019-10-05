<?php

namespace App\Repository;

use App\NotaDebito;
use Illuminate\Support\Facades\DB;

class NotaDebitoRepository{

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de traer las notas que van hacer enviadas a la DIAN
     @return:  array-object
     */    
    public static function getNotasEnviar(){
        $param='09202019';
        $notas = NotaDebito::join('companias','codigo','=',DB::raw('(nd_codcompania + 0)'))
        ->whereRaw("to_date(to_char(nd_feccrea,'%Y%m%d'),'%Y%m%d') >=  to_date('{$param}','%m%d%Y') ")        
        ->get();
     //    ->toSql(); //para ver le SQL
     
        return $notas;
     }

}