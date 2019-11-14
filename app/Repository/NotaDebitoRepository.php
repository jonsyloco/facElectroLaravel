<?php

namespace App\Repository;

use App\NotaDebito;
use App\TablaLogNd;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotaDebitoRepository
{

  /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de traer las notas que van hacer enviadas a la DIAN
     @return:  array-object
     */
  public static function getNotasEnviar_()
  {
    $param = '09192019';
    $notas = NotaDebito::join('companias', 'codigo', '=', DB::raw('(nd_codcompania + 0)'))
      ->whereRaw("to_date(to_char(nd_feccrea,'%Y%m%d'),'%Y%m%d') >=  to_date('{$param}','%m%d%Y') ")
      ->where('nd_canc_numnot', '=', '63019649')
      ->get();
    //   ->toSql(); //para ver le SQL

    return $notas;
  }

  public static function getNotasEnviar()
  {
    $param = '11062019';
    $notas = NotaDebito::join('companias', 'codigo', '=', DB::raw('(nd_codcompania + 0)'))
      ->whereRaw("to_date(to_char(nd_feccrea,'%Y%m%d'),'%Y%m%d') >=  to_date('{$param}','%m%d%Y') ")
      //   ->where('nd_canc_numnot','=','738741')      
      ->get();
    //   ->toSql(); //para ver le SQL

    return $notas;
  }

  /*
     @autor: Jhonatan W. ocampo
     @Fecha: 11/10/2019
     @Descripcion: Metodo encargado de guardar en la tabla de log
     el resultado de haber enviado la nota a la DIAN
     @return:  
     */
  public static function insertTablaLogNd($td_factura, $td_cadv_numdoc, $td_canc_numnot, $td_nuoren_nota, $td_observ, $td_cadv_clase, $td_cufe, $td_fec_nota, $td_hora_nota, $td_sucur, $td_vlr_nota, $td_canc_nitcli, $td_dpto, $td_codmuni,  $trackid, $td_estado = "2", $td_ruta_xml = "", $td_nomb_xml = "")
  {

    $fechaHoy = Carbon::now();

    $log = new TablaLogNd();
    $log->td_factura = $td_factura;
    $log->td_cadv_numdoc = $td_cadv_numdoc;
    $log->td_nuoren_nota = $td_nuoren_nota;
    $log->td_canc_numnot = $td_canc_numnot;
    $log->td_estado = $td_estado;
    $log->td_ruta_xml = $td_ruta_xml;
    $log->td_nomb_xml = $td_nomb_xml;
    $log->td_observ = $td_observ;
    $log->td_cadv_clase = $td_cadv_clase;
    $log->td_cufe = $td_cufe;
    $log->td_fec_nota = $td_fec_nota;
    // $log->hora_fact = $fechaHoy->toTimeString();
    $log->td_sucur = $td_sucur;
    $log->td_vlr_nota = $td_vlr_nota;
    $log->td_canc_nitcli = $td_canc_nitcli;
    $log->td_dpto = $td_dpto;
    $log->td_codmuni = $td_codmuni;
    $log->td_trackid = $trackid;
    $log->td_hora_nota = $fechaHoy->toTimeString();
    $log->td_horacrea = $fechaHoy->toTimeString();
    $log->td_feccrea = $fechaHoy->format('m/d/Y');
    $log->td_usrcrea = 'SYSTEMA';

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
        $notas = TablaLogNd::where('td_estado', '1')
            ->whereNotNull('td_trackid')
            ->whereRaw("td_trackid <> ''")
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
        TablaLogNd::where('td_consec', $id)->update([
            'td_estado' => $estado,
            'td_observ' => $observacion,
            'td_cufe' => $cufe,
        ]);
    }
}
