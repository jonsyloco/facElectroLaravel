<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

/**HELPERSSSSSSSSSSSSSSSSSSSSS */
class Helper
{

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 27/09/2019
     @Descripcion: Metodo que crea un log en la carpeta PUBLIC
     @return:  
     */
    public static function crearLog($texto, $nombre_log = 'log_factura_eletronica', $ruta_log = '../public/log/')
    {
        $fecha = Carbon::now();
        $fecha->format('d/m/Y');
        $nombreArchivo = "{$nombre_log}{$fecha->format('d')}_{$fecha->format('m')}_{$fecha->format('Y')}";
        $file = fopen("{$ruta_log}{$nombreArchivo}.txt", "a");
        fwrite($file, "******************************************" . PHP_EOL);
        fwrite($file, "Inicio -> {$fecha->format('d/m/Y h:i:s A')}" . PHP_EOL);
        fwrite($file, $texto . PHP_EOL);
        fwrite($file, "Fin -> {$fecha->format('d/m/Y h:i:s A')}" . PHP_EOL);
        fclose($file);
    }

     /**
     * funcion que permite redondear una valores de acuerdo norma de la dian
     * autor:fredy ocampo
     */
    public  static function redondearDian($numero)
    {
        $resultado = 0;
        $parte_entera = $numero;
        $parte_decimal = 0;
        $siguiente_menos_significativo = 0;
        $segundo__siguiente_menos_significativo = 0;
        $digito_menos_significativo = 0;

        $pos = strpos($numero, '.');

        if ($pos !== false) { //si contiene decimales
            $parte_numero = explode(".", $numero); //separamos decimales separados por coma
            $parte_entera = $parte_numero[0];
            $parte_decimal = $parte_numero[1];
        }

        if (strlen($parte_decimal) <= 2) {
            $resultado =  $numero;
        } else //si es mayor o igual que 3
        {

            $siguiente_menos_significativo =  $parte_decimal[2]; //cogemos el tercer digito de la parte decimal

            if ($siguiente_menos_significativo >= 0 && $siguiente_menos_significativo <= 4) {
                $digito_menos_significativo = $parte_decimal[0] .$parte_decimal[1];
            }

            if ($siguiente_menos_significativo >= 6 && $siguiente_menos_significativo <= 9) {
                $digito_menos_significativo = ($parte_decimal[0] . $parte_decimal[1]) + 1;
            }

            if ($siguiente_menos_significativo == 5) {

                if (strlen($parte_decimal) == 3) {
                    $segundo__siguiente_menos_significativo = 0;
                } else {
                    $segundo__siguiente_menos_significativo = $parte_decimal[3];
                }

                if ($segundo__siguiente_menos_significativo % 2 == 0) { //si es par redondea hacia abajo
                    $digito_menos_significativo = $parte_decimal[0] .$parte_decimal[1];
                } else {
                    $digito_menos_significativo = ($parte_decimal[0] . $parte_decimal[1]) + 1; //si es impar se redondea hacia arriva
                }
            }

            $resultado =  $parte_entera . "." .  $digito_menos_significativo;
        }


        return $resultado;
    }
}
