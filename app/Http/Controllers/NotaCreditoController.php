<?php

namespace App\Http\Controllers;

use App\Helpers\FuncionesHelper;
use App\Repository\NotaCreditoRepository;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Helper;

class NotaCreditoController extends Controller
{
    //
    public function index()
    {
        echo "hola  mundo";
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 30/09/2019
     @Descripcion: Metodo encargado de obtener el resultado de las notas creditos que se han enviado
     a la DIAN 
     @return: json 
     */
    public function getNotaCredito()
    {

        // $trackId = "a307912e-24ab-43d4-bad3-8c436327e275";
        // $trackId = "de993262-d6b5-4b88-bb6c-7db3ae8b1783";
        // $trackId = "aa584f3a-0700-4182-8c94-108a133d2cac";
        // $trackId = "fffe813f-28c8-4155-918e-5b6d64131290";
        // $trackId = "862fb3f9-c8e9-4fc7-8547-901590c6cf6d";
        // $trackId = "8a39b5d9-42dd-4a78-8dee-409693b8bd08";
        $trackId = "c4f87afe-ee31-49d8-8a66-a0d61f331115";

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
     @Fecha: 30/09/2019
     @Descripcion: Metodo encargado de enviar notas credito a la DIAN ,
     solo con parametros quemados
     @return:  json
     */
    public function sendNotaCredito()
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
        $nota['number'] = "12";
        /**numero a ITERAR -> # de la nota */
        $nota['from'] = "";
        $nota['to'] = "";
        $nota['technical_key'] = "";
        $nota['resolution'] = "";
        $nota['resolution_date'] = "";
        $nota['date_from'] = "";
        $nota['date_to'] = "";
        $nota['type_document_id'] = 4;
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
            "id" => 4,
            "name" => "Nota Crédito",
            "code" => "91",
            "cufe_algorithm" => "CUDE-SHA384",
            "prefix" => "nc"
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

        $nota['legal_monetary_totals'] = array(

            "line_extension_amount" => "2300",
            "tax_exclusive_amount" => "2000",
            "tax_inclusive_amount" => "2300",
            "allowance_total_amount" => "0",
            "charge_total_amount" => "0",
            "payable_amount" => "2300"
        );


        $nota['credit_note_lines'] = array(

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

                "description" => "articulo excento de IVA",
                "code" => "1111111",
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
        $response = $client->request("POST", "credit-note/$trackPruebas", ['body' => json_encode($nota)]);

        // print_r($response->getBody()->getContents());
        // die;


        $resp = json_decode($response->getBody()->getContents(), true);


        return $resp;
        // return $resp['ResponseDian']['Envelope']['Body'];
    }


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de enviar notas credito a la DIAN ,
     Conexion a informix
     @return:  json
     */
    public function sendNotaCredito2()
    {


        $notas = NotaCreditoRepository::getNotasEnviar();
        $numeroActual = 29;

        // $trackPruebas = "ff244060-36c7-4da2-a228-016827608afe"; //identificador de pruebas
        $trackPruebas = "15fd46ec-ba52-49a7-ac65-c2fc215081cf"; //identificador de pruebas


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


        foreach ($notas as $nota_c) {
            // print_r($nota_c->nombre);
            // die;

            $nota = array();

            /**datos del certificado */
            $nota['certificate_name'] = "8900016003.p12";
            $nota['certificate_pass'] = "7pC9u9bCEV";
            $nota['sw_identifier'] = "0eb9f032-c0f8-4140-8f1e-afe5188f3834";
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
            $nota['type_document_id'] = 4; //nota credito
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
                "id" => 4,
                "name" => "Nota Crédito",
                "code" => "91",
                "cufe_algorithm" => "CUDE-SHA384",
                "prefix" => "nc"
            );

            $nota["billing_reference"] = array(
                "number" => 'SETP990000016',
                // "number" => $nota_c->nc_numfact,
                // "uuid" => $nota_c->nc_cufe_fact, debe tener 96 caracteres la CUFE
                "uuid" => '6455bdd3db121d3536076a53fb4e19b4f87638dad9c3a38df99d870b322e5284b58853a02679d28ec988d9102b7eaef8',
                "issue_date" => Carbon::parse($nota_c->nc_fecimp_fact)->format('Y-m-d')
            );

            //tratamiento de datos de cliente

            $cliente = array();
            $cliData = $nota_c->nc_datos_cliente;
            if ($cliData != "" || (empty($cliData) == false)) {
                $cliData = explode('|', $cliData);
                $cliente['cedula'] = trim($cliData[1]);
                $cliente['p_apellido'] = utf8_encode(trim($cliData[11]));
                $cliente['s_apellido'] = utf8_encode(trim($cliData[12]));
                $cliente['p_nombre'] = utf8_encode(trim($cliData[9]));
                $cliente['s_nombre'] = utf8_encode(trim($cliData[10]));
                $cliente['dir'] = utf8_encode(trim($cliData[4]));

                $cliente['barrio'] = utf8_encode(trim($cliData[4]));
                $cliente['telefono'] = trim($cliData[13]);
                $cliente['dv'] = trim($cliData[14]);
                $cliente['email'] = trim($cliData[15]);
                $cliente['tpDoc'] = trim($cliData[2]);
                $cliente['nombres'] = trim($cliData[0]);
                if ($cliente['tpDoc'] == 31) { //si el cliente es juridico
                    $cliente['razon_social'] = trim($cliData[0]);
                } else { //es persona natural
                    $cliente['razon_social'] = "";
                }
            }


            $nota['customer'] = array(
                "type_document_identification" => $cliente['tpDoc'],
                "identification_number" => $cliente['cedula'],
                "name" => $cliente['razon_social'],
                "phone" => $cliente['telefono'],
                "address" => $cliente['dir'],
                "email" => $cliente['email'], //HAY QUE TRAER EL MAIL
                "merchant_registration" => "No tiene"
            );

            //definicion de los impuestos de una NOTA IVA,RETEIVA,RETEFUENTE
            $array_impuestos = array();
            $array_impuestos = $this->getImpuestos($nota_c);

            // die(" - {$nota_c->nc_vlrtotal_nota}");

            $tax_exclusive_amount = 0; //base para calcular los impuestos de la NOTA
            if ((int) $nota_c->nc_porciva != 0 && (int) $nota_c->nc_iva != 0) {
                $tax_exclusive_amount += $nota_c->nc_vlr_basenota;
            }
            $nota['legal_monetary_totals'] = array(

                "line_extension_amount" => $nota_c->nc_vlrtotal_nota,
                "tax_exclusive_amount" => $tax_exclusive_amount,
                "tax_inclusive_amount" => $nota_c->nc_vlrtotal_nota,
                "allowance_total_amount" => "0",
                "charge_total_amount" => "0",
                "payable_amount" => $nota_c->nc_vlrtotal_nota
            );


            $nota['credit_note_lines'] = array(
                0 => array(
                    "unit_measure_id" => 70,
                    "invoiced_quantity" => "1",
                    "line_extension_amount" => $nota_c->nc_vlrtotal_nota,
                    "free_of_charge_indicator" => false,

                    "allowance_charges" => array(
                        0 => array(
                            "charge_indicator" => false,
                            "allowance_charge_reason" => "Discount",
                            "amount" => "0",
                            "base_amount" => "0"
                        )
                    ),
                    "tax_totals" => $array_impuestos,

                    "description" => $nota_c->nc_detalle,
                    "code" => "1111111",
                    "type_item_identification_id" => 3,
                    "price_amount" => $nota_c->nc_vlrtotal_nota,
                    "base_quantity" => "1",

                    "unit_measure" => array(
                        "code" => "94"
                    )
                )

            );

            $response = "";

            $response = $client->requestAsync("POST", "credit-note/$trackPruebas", ['body' => json_encode($nota)]);
            $response = $response->wait();

            $resultado = $response->getBody()->getContents();

            $nombre_log = "log_nota_credito_";
            Helper::crearLog($resultado, $nombre_log);

            $numeroActual++;

            $numero = $nota['number']; //numero de la nota

            if ($response->getStatusCode() == 200) { //si el servidor respondio... verificamos el estado del mensaje

                $resp = array();
                $resp = json_decode($resultado, true);
                // print_r($resp);
                print_r($resp['message']);
                echo "\n**********************************************************";
                print_r($resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']);
                $ruta_xml = $this->guardarComprimido($nota_c->numnit, $nota_c->nc_sucur, $resp['ZipBase64Bytes'], $numero);

                if (array_key_exists('XmlParamsResponseTrackId', $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList'])) {
                    $mensajeError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['ProcessedMessage'];
                    $codigoError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['SenderCode'];

                    NotaCreditoRepository::insertTablaLogNc(
                        $nota_c->nc_numfact,
                        $numero,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $nota_c->nc_cufe_fact,
                        $nota_c->nc_fenot,                        
                        $nota_c->nc_sucur,
                        $nota_c->nc_vlr_basenota,
                        $nota_c->nc_nitcli,
                        $nota_c->nc_coddpto,
                        $nota_c->nc_codmuni,
                        '', //TRACKID nulo
                        "2",
                        $ruta_xml,
                        "$numero.zip"
                    );
                    continue;
                }

                if (is_array($resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']) == false) { //respuesta esperada y correcta
                    $t_id = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']; // trackID


                    NotaCreditoRepository::insertTablaLogNc(
                        $nota_c->nc_numfact,
                        $numero,
                        "{$resp['message']}",
                        $nota_c->nc_cufe_fact,
                        $nota_c->nc_fenot,
                        $nota_c->nc_sucur,
                        $nota_c->nc_vlr_basenota,
                        $nota_c->nc_nitcli,
                        $nota_c->nc_coddpto,
                        $nota_c->nc_codmuni,
                        $t_id,
                        "1",
                        $ruta_xml,
                        "$numero.zip"
                    );

                    continue;
                } else {


                    NotaCreditoRepository::insertTablaLogNc(
                        $nota_c->nc_numfact,
                        $numero,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $nota_c->nc_cufe_fact,
                        $nota_c->nc_fenot,
                        $nota_c->nc_sucur,
                        $nota_c->nc_vlr_basenota,
                        $nota_c->nc_nitcli,
                        $nota_c->nc_coddpto,
                        $nota_c->nc_codmuni,
                        '', //TRACKID nulo
                        "2",
                        $ruta_xml,
                        "$numero.zip"
                    );

                    continue;
                }
                echo "\nbase 64 xml\n";
                print_r($resp['ZipBase64Bytes']);
            }
        }
        return "ok";
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 01/10/2019
     @Descripcion: Metodo encargado de  obtener los impuestos de las NOTAS
     y acoplarlos en un ARRAY 
     @return:  array
     */
    public function getImpuestos($nota)
    {

        $array = array();

        if ($nota->nc_porciva != 0 && $nota->nc_iva != 0) { //IVA
            $iva = 0;
            $iva = $nota->nc_vlr_basenota * ($nota->nc_porciva / 100);
            $iva = Helper::redondearDian($iva);
            $nota->nc_iva = round($iva);
            /**re-asignacion IVA*/

            array_push($array, array(
                "tax_id" => 1,
                "tax_amount" => $nota->nc_iva,
                "taxable_amount" => $nota->nc_vlr_basenota,
                "percent" => $nota->nc_porciva
            ));
        }
        if ($nota->nc_porc_reteiva != 0 && $nota->nc_reteiva != 0 && $nota->nc_porciva != 0) { //RETEIVA

            array_push(
                $array,
                array(
                    "tax_id" => 5,
                    "tax_amount" => $nota->nc_reteiva,
                    "taxable_amount" => $nota->nc_iva,
                    "percent" => $nota->nc_porc_reteiva
                )
            );
        }
        if ($nota->nc_retefuente != 0 && $nota->nc_porc_retefuente != 0) { //retefuente

            array_push($array, array(
                "tax_id" => 6,
                "tax_amount" => $nota->nc_retefuente,
                "taxable_amount" => $nota->nc_vlr_basenota,
                "percent" => $nota->nc_porc_retefuente
            ));
        }

        $nota->nc_vlrtotal_nota = $nota->nc_iva + $nota->nc_vlr_basenota;
        /**re-asignando el total */

        return $array;
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 16/10/2019
     @Descripcion: Metodo encargado de guardar los comprimidos en al ruta especifica
     @return: string ruta LOG 
     */
    public function guardarComprimido($nitEmpr2, $sucursal, $xml_zip, $nombre_archivo)
    {

        $fecha = Carbon::now();
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $dia = $fecha->format('d');

        $rutaFinalXML_compri = "XML_NOTAS_CREDITO/$nitEmpr2/$sucursal/$anio/$mes/$dia/";

        if (!file_exists($rutaFinalXML_compri)) { //si no existe la carpeta la crea
            if (mkdir($rutaFinalXML_compri, 0777, true) === false) {
                echo "carpeta no creada";
            }
        }

        $zip_contents = $xml_zip;
        $file = $rutaFinalXML_compri . $nombre_archivo . '.zip';
        file_put_contents($file, base64_decode(base64_decode($zip_contents)));
        return $file;
    }
}
