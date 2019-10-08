<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repository\NotaDebitoRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotaDebitoController extends Controller
{
    //
    public function index()
    {
        Helper::crearLog('ejemplo helper desde nota debito', 'log_nota_debito');
        return "hola mundo index";
    }

    public function getResultNota()
    {

        $trackId = "8a39b5d9-42dd-4a78-8dee-409693b8bd08";

        $token = "FHMoDO27s4eFseLijLiDibSjKuAn3r1mBHmrPcaaZOZbz1ohy4U9kYfb6fXsSYrrWIFfdwVCCYH2MZpl";

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
        return $resp['ResponseDian']['Envelope']['Body'];
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 03/10/2019
     @Descripcion: Metodo encargado de enviar una nota con parametros quemados para pruebas
     @return: json 
     */
    public function sendNotaDebito()
    {
        $trackPruebas = "ff244060-36c7-4da2-a228-016827608afe"; //identificador de pruebas

        $token = "FHMoDO27s4eFseLijLiDibSjKuAn3r1mBHmrPcaaZOZbz1ohy4U9kYfb6fXsSYrrWIFfdwVCCYH2MZpl";

        $client = new Client([
            'base_uri' => 'localhost/api-dian-master/public/api/ubl2.1/',
            // 'base_uri' => '127.0.0.1:8000/api/ubl2.1/status/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);

        $nota = array();
        /**datos del certificado */
        $nota['certificate_name'] = "8900016003.p12";
        $nota['certificate_pass'] = "7pC9u9bCEV";
        $nota['sw_identifier'] = "c8784166-6c81-4361-99aa-15c28a523d41";
        $nota['sw_pin'] = "14082";
        $nota['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
        $nota['identification_number'] = "890001600";

        /*datos de la nota */
        $nota['prefix'] = "";
        $nota['number'] = "5";
        /**numero a ITERAR -> # de la nota */
        $nota['from'] = "";
        $nota['to'] = "";
        $nota['technical_key'] = "";
        $nota['resolution'] = "";
        $nota['resolution_date'] = "";
        $nota['date_from'] = "";
        $nota['date_to'] = "";
        $nota['type_document_id'] = 5;
        $nota['company_id'] = 1;

        $nota['payment_form'] = array( //forma de pago
            "payment_form_id" => "1",
            "payment_method_id" => "10",
            "payment_id" => 1,
            "name" => "Contado",
            "code" => "1",
            "payment_method_code" => array(
                "id" => 10,
                "name" => "Efectivo",
                "code" => "10"
            )
        );

        $nota['TypeDocument'] = array( //tipo de documento
            "id" => 5,
            "name" => "Nota Débito",
            "code" => "92",
            "cufe_algorithm" => "CUDE-SHA384",
            "prefix" => "nd"
        );

        $nota["billing_reference"] = array(

            "number" => "SETP990000016",
            "uuid" => "6455bdd3db121d3536076a53fb4e19b4f87638dad9c3a38df99d870b322e5284b58853a02679d28ec988d9102b7eaef8",
            "issue_date" => "2019-09-06"


        );

        $nota['customer'] = array(
            "type_document_identification" => 13,
            "identification_number" => 1234567890,
            "name" => "Customer Test",
            "phone" => 1234567,
            "address" => "CALLE 0 0C 0",
            "email" => "test@test.com",
            "merchant_registration" => "No tiene"
        );

        $nota['requested_monetary_totals'] = array(

            "line_extension_amount" => "2300",
            "tax_exclusive_amount" => "2000",
            "tax_inclusive_amount" => "2300",
            "allowance_total_amount" => "0",
            "charge_total_amount" => "0",
            "payable_amount" => "2300"
        );


        $nota['debit_note_lines'] = array(

            0 => array(
                "unit_measure_id" => 70,
                "invoiced_quantity" => "1",
                "line_extension_amount" => "2300",
                "free_of_charge_indicator" => false,

                "allowance_charges" => array(
                    0 => array(
                        "charge_indicator" => false,
                        "allowance_charge_reason" => "Discount",
                        "amount" => "0",
                        "base_amount" => "0"
                    )
                ),
                "tax_totals" => array(
                    0 => array(
                        "tax_id" => 1,
                        "tax_amount" => "380",
                        "taxable_amount" => "2000",
                        "percent" => "19"
                    ),
                    1 => array(
                        "tax_id" => 6,
                        "tax_amount" => "80",
                        "taxable_amount" => "2000",
                        "percent" => "4"
                    )

                ),

                "description" => "devolucion de mercancia de cliente",
                "code" => "1234564681654",
                "type_item_identification_id" => 3,
                "price_amount" => "2300",
                "base_quantity" => "1",

                "unit_measure" => array(
                    "code" => "94"
                )
            )


        );

        // print_r(json_encode($nota));die;


        $response = "";
        $response = $client->request("POST", "debit-note/$trackPruebas", ['body' => json_encode($nota)]);

        // print_r($response->getBody()->getContents());
        // die;


        $resp = json_decode($response->getBody()->getContents(), true);


        return $resp;
        // return $resp['ResponseDian']['Envelope']['Body'];
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 03/10/2019
     @Descripcion: Metodo encargado de enviar notas debito,
     obteniendo los datos de la BD
     @return:  ??
     */
    public function sendNotaDebito2()
    {
        ini_set('max_execution_time', '0');

        $notas = NotaDebitoRepository::getNotasEnviar();
        // print_r($notas);die;

        $numeroActual = 65;

        $trackPruebas = "ff244060-36c7-4da2-a228-016827608afe"; //identificador de pruebas

        $token = "FHMoDO27s4eFseLijLiDibSjKuAn3r1mBHmrPcaaZOZbz1ohy4U9kYfb6fXsSYrrWIFfdwVCCYH2MZpl";

        $client = new Client([
            'base_uri' => 'localhost/api-dian-master/public/api/ubl2.1/',
            // 'base_uri' => '127.0.0.1:8000/api/ubl2.1/status/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);


        foreach ($notas as $nota_d) {
            // print_r($nota_d->nombre);
            // die;

            $nota = array();

            /**datos del certificado */
            $nota['certificate_name'] = "8900016003.p12";
            $nota['certificate_pass'] = "7pC9u9bCEV";
            $nota['sw_identifier'] = "c8784166-6c81-4361-99aa-15c28a523d41";
            $nota['sw_pin'] = "14082";
            $nota['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
            $nota['identification_number'] = "890001600";


            /*datos de la nota */
            $nota['prefix'] = "";
            $nota['number'] = $numeroActual; //numero a ITERAR -> # de la nota
            $nota['from'] = "";
            $nota['to'] = "";
            $nota['technical_key'] = "";
            $nota['resolution'] = "";
            $nota['resolution_date'] = "";
            $nota['date_from'] = "";
            $nota['date_to'] = "";
            $nota['type_document_id'] = 5; //nota debito
            $nota['company_id'] = 1;

            $nota['payment_form'] = array( //forma de pago
                "payment_form_id" => "1",
                "payment_method_id" => "10",
                "payment_id" => 1,
                "name" => "Contado",
                "code" => "1",
                "payment_method_code" => array(
                    "id" => 10,
                    "name" => "Efectivo",
                    "code" => "10"
                )
            );

            $nota['TypeDocument'] = array( //tipo de documento
                "id" => 5,
                "name" => "Nota Débito",
                "code" => "92",
                "cufe_algorithm" => "CUDE-SHA384",
                "prefix" => "nd"
            );

            $nota["billing_reference"] = array(
                "number" => 'SETP990000016',
                // "number" => $nota_d->nd_numfact,
                // "uuid" => $nota_d->nd_cufe_fact, debe tener 96 caracteres la CUFE
                "uuid" => '6455bdd3db121d3536076a53fb4e19b4f87638dad9c3a38df99d870b322e5284b58853a02679d28ec988d9102b7eaef8',
                "issue_date" => Carbon::parse($nota_d->nd_cadv_fecha_imp)->format('Y-m-d')
            );

            //tratamiento de datos de cliente

            $cliente = array();
            $cliData = $nota_d->nd_datos_cliente;
            if ($cliData != "" || (empty($cliData) == false)) {
                $cliData = explode('|', $cliData);
                $cliente['cedula'] = trim($cliData[0]);
                $cliente['p_apellido'] = utf8_encode(trim($cliData[1]));
                $cliente['s_apellido'] = utf8_encode(trim($cliData[2]));
                $cliente['p_nombre'] = utf8_encode(trim($cliData[3]));
                $cliente['s_nombre'] = utf8_encode(trim($cliData[4]));
                $cliente['dir'] = utf8_encode(trim($cliData[5]));
                $cliente['barrio'] = utf8_encode(trim($cliData[6]));
                $cliente['telefono'] = trim($cliData[7]);
                $cliente['dv'] = trim($cliData[12]);
                $cliente['tpDoc'] = trim($cliData[11]);
                $cliente['nombres'] = trim($cliData[13]);
                if ($cliente['tpDoc'] == 31) { //si el cliente es juridico
                    $cliente['razon_social'] = trim($cliData[14]);
                } else { //es persona natural
                    $cliente['razon_social'] = "";
                }
            }


            $nota['customer'] = array(
                "type_document_identification" => $cliente['tpDoc'],
                "identification_number" => $cliente['cedula'],
                "name" => empty($cliente['razon_social']) ? $cliente['nombres'] : $cliente['razon_social'],
                "phone" => $cliente['telefono'],
                "address" => $cliente['dir'],
                "email" => "test@test.com", //HAY QUE TRAER EL MAIL
                "merchant_registration" => "No tiene"
            );


            //definicion de los impuestos de una NOTA IVA,RETEIVA,RETEFUENTE


            // die(" - {$nota_d->nd_vlrtotal_nota}");

            $tax_exclusive_amount = 0; //base para calcular los impuestos de la NOTA
            if ((int) $nota_d->nd_porciva != 0 && (int) $nota_d->nd_vlriva != 0) {
                $tax_exclusive_amount += $nota_d->nd_canc_vlrnot - $nota_d->nd_vlriva;
            }

            $iva = 0; //iva de la NOTA
            if ((int) $nota_d->nd_porciva != 0 && (int) $nota_d->nd_vlriva != 0) {
                $iva = (int) $nota_d->nd_vlriva;
            }

            $nota['requested_monetary_totals'] = array(
                "line_extension_amount" => (int) $nota_d->nd_canc_vlrnot,
                "tax_exclusive_amount" => $tax_exclusive_amount,
                "tax_inclusive_amount" => (int) $nota_d->nd_canc_vlrnot,
                "allowance_total_amount" => "0",
                "charge_total_amount" => "0",
                "payable_amount" => (int) $nota_d->nd_canc_vlrnot
            );


            $nota['debit_note_lines'] = array(
                0 => array(
                    "unit_measure_id" => 70,
                    "invoiced_quantity" => "1",
                    "line_extension_amount" => (int) $nota_d->nd_canc_vlrnot,
                    "free_of_charge_indicator" => false,

                    "allowance_charges" => array(
                        0 => array(
                            "charge_indicator" => false,
                            "allowance_charge_reason" => "Discount",
                            "amount" => "0",
                            "base_amount" => "0"
                        )
                    ),
                    "tax_totals" => array(
                        0 => array(

                            "tax_id" => 1,
                            "tax_amount" => $iva,
                            "taxable_amount" => $tax_exclusive_amount,
                            "percent" => $nota_d->nd_porciva
                        ),
                    ),

                    "description" => $nota_d->nd_canc_detalle,
                    "code" => "1111111",
                    "type_item_identification_id" => 3,
                    "price_amount" => (int) $nota_d->nd_canc_vlrnot,
                    "base_quantity" => "1",

                    "unit_measure" => array(
                        "code" => "94"
                    )
                )

            );
            // print_r($nota);
            // die;

            $response = "";
            $response = $client->request("POST", "debit-note/$trackPruebas", ['body' => json_encode($nota)]);

            // print_r($response->getBody()->getContents());
            // die;

            // $resp = json_decode($response->getBody()->getContents(), true);
            $resp = $response->getBody()->getContents();

            $nombre_log = "log_nota_debito_";
            Helper::crearLog($resp, $nombre_log);

            $numeroActual++;
            // return $resp['ResponseDian']['Envelope']['Body'];
        }
        return "ok";

        


    }
}
