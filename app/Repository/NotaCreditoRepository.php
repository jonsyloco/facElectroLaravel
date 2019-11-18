<?php

namespace App\Repository;

use App\Compania;
use App\NotaCredito;
use App\Sucursal;
use App\TablaLogNc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotaCreditoRepository
{


   /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de traer las notas que van hacer enviadas a la DIAN
     @return:  array-object
     */
   public static function getNotasEnviar()
   {
      $notas = NotaCredito::join('companias', 'codigo', '=', DB::raw('(nc_codcompania + 0)'))
         ->whereRaw('nc_feccrea >= today-10')
         ->whereNotNull('nc_cufe_fact')
         ->whereRaw("trim(nc_cufe_fact) <> ''")
         ->where('nc_mc_numdoc', '=', '16141')
         // ->limit(1)
         ->get();
      //    ->toSql(); //para ver le SQL

      return $notas;
   }

   /*
     @autor: Jhonatan W. ocampo
     @Fecha: 11/10/2019
     @Descripcion: Metodo encargado de guardar en la tabla de log
     el resultado de haber enviado la nota a la DIAN
     @return:  
     */
   public static function insertTablaLogNc($tn_factura,  $tn_mc_numnot, $tn_observ, $tn_cufe, $tn_fecnot, $tn_sucur, $tn_vlrtotal, $tn_mc_nitcli, $tn_coddpto, $tn_codmuni,  $trackid, $tn_estado = "2", $tn_rutaxml = "", $tn_nombxml = "")
   {

      $fechaHoy = Carbon::now();

      $log = new TablaLogNc();
      $log->tn_factura = $tn_factura;
      $log->tn_mc_numnot = $tn_mc_numnot;
      $log->tn_estado = $tn_estado;
      $log->tn_rutaxml = $tn_rutaxml;
      $log->tn_nombxml = $tn_nombxml;
      $log->tn_observ = $tn_observ;
      $log->tn_cufe = $tn_cufe;
      $log->tn_fecnot = $tn_fecnot;
      $log->tn_sucur = $tn_sucur;
      $log->tn_vlrtotal = $tn_vlrtotal;
      $log->tn_mc_nitcli = $tn_mc_nitcli;
      $log->tn_coddpto = $tn_coddpto;
      $log->tn_codmuni = $tn_codmuni;
      $log->tn_trackid = $trackid;
      $log->tn_horanot = $fechaHoy->toTimeString();
      $log->tn_horacrea = $fechaHoy->toTimeString();
      $log->tn_feccrea = $fechaHoy->format('m/d/Y');
      $log->tn_usrcrea = 'SYSTEMA';

      return $log->save();
   }

   /*
     @autor: Jhonatan W. ocampo
     @Fecha: 16/10/2019
     @Descripcion: Metodo encargado de obtener las facturas en estado 1 para obtener le resultado real de la DIAN
     @return: array 
     */
   public static function obtenerNotas()
   {
      $notas = TablaLogNc::where('tn_estado', '1')
         ->whereNotNull('tn_trackid')
         ->whereRaw("tn_trackid <> ''")
         // ->limit(3)
         ->get();
      // ->toSql();

      return $notas;
   }

   /*
     @autor: Jhonatan W. ocampo
     @Fecha: 16/10/2019
     @Descripcion: Metodo encargado de actualizar el estado en la tabla de LOGS
     de las notas debito, este estado y descripcion son actualizados, justo despues de
     enviar a preguntar con el TRACKID si la nota fue aceptada 
     @return:  ??
     */
   public static function updateEstadoNota($id, $estado, $observacion, $cufe = '')
   {
      TablaLogNc::where('tn_consec', $id)->update([
         'tn_estado' => $estado,
         'tn_observ' => $observacion,
         'tn_cufe' => $cufe,
      ]);
   }
}
