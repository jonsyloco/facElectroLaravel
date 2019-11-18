<?php

namespace App\Http\Controllers;

use App\Repository\NotaCreditoRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ResultNotaCrController extends Controller
{
    public function index()
    {
        return "controlador:ResultNotaCrController, metodo:index";
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 16/10/2019
     @Descripcion: Metodo encargado de obtener el resultado de las notas y actualizar la tabla de LOGS
     @return: ?? 
     */
    public function obtenerResulNotaPendientes()
    {
        // return "controlador:ResultNotaCrController, metodo:obtenerResulNotaPendientes";
        ini_set('max_execution_time', 0);
        $notas = NotaCreditoRepository::obtenerNotas();
        if (count($notas) == 0) {
            echo "No hay Notas para evaluar";
            return;
        }

        $token = "FHMoDO27s4eFseLijLiDibSjKuAn3r1mBHmrPcaaZOZbz1ohy4U9kYfb6fXsSYrrWIFfdwVCCYH2MZpl";

        foreach ($notas as $nota_c) {

            $trackId = $nota_c->tn_trackid;
            $client = new Client([
                'base_uri' => 'localhost/api-dian-master/public/api/ubl2.1/status/',
                // 'base_uri' => '127.0.0.1:8000/api/ubl2.1/status/',
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $response = "";
            $response = $client->request("POST", "zip/$trackId");

            // print_r($response->getBody()->getContents());


            $resp = json_decode($response->getBody()->getContents(), true);

            // return $resp;
            // print_r($resp['ResponseDian']['Envelope']['Body']);


            print_r(count($resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['ErrorMessage']));
            if (count($resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['ErrorMessage']) > 0) {  //si hay errores
                //hay errores
                $descripcion = $resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['StatusDescription'];
                $IUUD = $resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['XmlDocumentKey'];
                $mensajes = json_encode($resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['ErrorMessage']);
                $consolidado = array();
                $consolidado['StatusDescription'] = $descripcion;
                $consolidado['CUFE'] = $IUUD;
                $consolidado['ErrorMessage'] = $mensajes;
                $errores = json_encode($consolidado);
                echo "<pre>";
                print_r($errores);
                NotaCreditoRepository::updateEstadoNota($nota_c->tn_consec, '3', $errores, $IUUD);
                //actualizamos el estado de la tabla de LOGS
                continue;
            } else {
                //no hay errores

                $descripcion = $resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['StatusDescription'];
                $IUUD = $resp['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse']['XmlDocumentKey'];
                $consolidado = array();
                $consolidado['StatusDescription'] = $descripcion;
                $consolidado['CUFE'] = $IUUD;
                $errores = json_encode($consolidado);
                echo "<pre>";
                print_r($errores);
                NotaCreditoRepository::updateEstadoNota($nota_c->tn_consec, '0', $errores, $IUUD);
            }
        }
        return "**fin**";
    }
}
