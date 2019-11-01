<?php

namespace App\Http\Controllers;

use App\Compania;
use App\Fact;
use App\Sucursal;
use App\Repository\FactRepository;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use stdClass;
use ZipArchive;

class FacturaController extends Controller
{
    public function index()
    {
        // $sucurs = Sucursal::on('informix')->obtenerCodigo()->where('codigo', 1)->get();
        // $compania = Compania::on('ibg_100_7')->get();
        $compania = Compania::on('ibg_100_7')->where('numnit', '890001600')->get();
        $fact = FactRepository::getFacturas();



        // $sucurs = Sucursal::select('nombre', 'personal.nombres')
        //     ->join('personal', 'personal.codsucur', '=', 'codigo')
        //     ->where('personal.estado','=','A')            
        //     ->get();

        // $sucurs = DB::connection('informix')->table('sucursales')->all();


        foreach ($fact as $factura) {
            dd($factura->compania->numnit);
            // dd($factura->fact_detalle);
            // echo "\n$sucursal->nombres\n";
            // die();
        }
        foreach ($compania as $compa) {
            dd($compa);
            // echo "\n$sucursal->nombres\n";
            // die();
        }
    }


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 24/09/2019
     @Descripcion: Metodo encargado de obtener el resultado de la factura segun la DIAN
     @return: json 
     */
    public function getResultFact()
    {

        // $trackId = "a307912e-24ab-43d4-bad3-8c436327e275";
        $trackId = "afe3523a-e992-48c6-bdb0-64ba931a3769";

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
     @Fecha: 24/09/2019
     @Descripcion: Metodo encargado de 
     @return: Metodo encargado de enviar facturas al API
     e insertar en base de datos 
     */
    public function sendInvoice()
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

        $factura = array();
        /**datos del certificado */
        $factura['certificate_name'] = "8900016003.p12";
        $factura['certificate_pass'] = "7pC9u9bCEV";
        $factura['sw_identifier'] = "c8784166-6c81-4361-99aa-15c28a523d41";
        $factura['sw_pin'] = "14082";
        $factura['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
        $factura['identification_number'] = "890001600";

        /*datos de la factura */
        $factura['prefix'] = "SETP";
        $factura['number'] = "990002027";
        $factura['from'] = "990000000";
        $factura['to'] = "995000000";
        $factura['technical_key'] = "fc8eac422eba16e22ffd8c6f94b3f40a6e38162c";
        $factura['resolution'] = "18760000001";
        $factura['resolution_date'] = "0001-01-01";
        $factura['date_from'] = "2019-01-19";
        $factura['date_to'] = "2030-01-19";
        $factura['type_document_id'] = 1;
        $factura['company_id'] = 1;

        $factura['payment_form'] = array( //forma de pago
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

        $factura['TypeDocument'] = array( //tipo de documento
            "id" => 1,
            "name" => "Factura de Venta Nacional",
            "code" => "01",
            "cufe_algorithm" => "CUFE-SHA384",
            "prefix" => "fv"
        );

        $factura['customer'] = array(
            "type_document_identification" => 13,
            "identification_number" => 1234567890,
            "name" => "Customer Test",
            "phone" => 1234567,
            "address" => "CALLE 0 0C 0",
            "email" => "test@test.com",
            "merchant_registration" => "No tiene"
        );

        $factura['legal_monetary_totals'] = array(

            "line_extension_amount" => "5000",
            "tax_exclusive_amount" => "0",
            "tax_inclusive_amount" => "5000",
            "allowance_total_amount" => "0",
            "charge_total_amount" => "0",
            "payable_amount" => "5000"
        );


        $factura['invoice_lines'] = array(

            0 => array(
                "unit_measure_id" => 70,
                "invoiced_quantity" => "1",
                "line_extension_amount" => "5000",
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
                        "tax_amount" => "0",
                        "taxable_amount" => "0",
                        "percent" => "0"
                    )

                ),

                "description" => "articulo excento de IVA",
                "code" => "1111111",
                "type_item_identification_id" => 3,
                "price_amount" => "5000",
                "base_quantity" => "1",

                "unit_measure" => array(
                    "code" => "94"
                )
            )


        );

        // print_r(json_encode($factura));die;


        $response = "";
        $response = $client->request("POST", "invoice/$trackPruebas", ['body' => json_encode($factura)]);

        // print_r($response->getBody()->getContents());
        // die;


        $resp = json_decode($response->getBody()->getContents(), true);


        return $resp;
        // return $resp['ResponseDian']['Envelope']['Body'];
    }


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 24/09/2019
     @Descripcion: Metodo encargado de 
     @return: Metodo encargado de enviar facturas al API
     e insertar en base de datos 
     */
    public function sendInvoice2()
    {
        ini_set('max_execution_time', 0);

        $fact = FactRepository::getFacturas(); //obtenemos todas las facturas

        $numeroActual = 990004026;
        // $trackPruebas = "ff244060-36c7-4da2-a228-016827608afe"; //identificador de pruebas
        $trackPruebas = "ecec6006-07eb-4946-be3c-7a3a17e4b3f1"; //identificador de pruebas

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

        foreach ($fact as $datos) {

            $factura = array();
            /**datos del certificado */
            $factura['certificate_name'] = "8900016003.p12";
            $factura['certificate_pass'] = "7pC9u9bCEV";
            // $factura['sw_identifier'] = "c8784166-6c81-4361-99aa-15c28a523d41";
            $factura['sw_identifier'] = "f4dfb118-4e37-4d28-a1aa-922230cb2057";
            $factura['sw_pin'] = "14082";
            $factura['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
            $factura['identification_number'] = $datos->compania->numnit;

            /*datos de la factura */
            $factura['prefix'] = "SETP"; //prefijo de la factura
            $factura['number'] = $numeroActual; //numero de la factura
            $factura['from'] = "990000000";
            $factura['to'] = "995000000";
            $factura['technical_key'] = "fc8eac422eba16e22ffd8c6f94b3f40a6e38162c";
            $factura['resolution'] = "18760000001";
            $factura['resolution_date'] = "0001-01-01";
            $factura['date_from'] = "2019-01-19";
            $factura['date_to'] = "2030-01-19";
            $factura['type_document_id'] = 1;
            $factura['company_id'] = 1;


            if ($datos->fact_id_tp_fact == 2) { //facuras tipo credito
                $factura['payment_form'] = array( //forma de pago
                    "payment_form_id" =>  $datos->fact_id_tp_fact,
                    "payment_method_id" => $datos->fact_id_meto_pago,
                    "payment_id" => $datos->fact_id_tp_fact,
                    "name" => $datos->fact_tpfact,
                    "code" => $datos->fact_id_tp_fact,
                    "payment_due_date" => '2019-12-10', //pendiente fecha de ultimo pago ***********************
                    "duration_measure" => '2', // numero de cuotas **********************************
                    "payment_method_code" => array(
                        "id" => $datos->fact_id_meto_pago,
                        "name" => $datos->fact_meto_pago,
                        "code" => $datos->fact_id_meto_pago,
                    )
                );
            } else { //facturas de contado
                $factura['payment_form'] = array( //forma de pago
                    "payment_form_id" =>  $datos->fact_id_tp_fact,
                    "payment_method_id" => $datos->fact_id_meto_pago,
                    "payment_id" => $datos->fact_id_tp_fact,
                    "name" => $datos->fact_tpfact,
                    "code" => $datos->fact_id_tp_fact,
                    "payment_method_code" => array(
                        "id" => $datos->fact_id_meto_pago,
                        "name" => $datos->fact_meto_pago,
                        "code" => $datos->fact_id_meto_pago,
                    )
                );
            }

            $factura['TypeDocument'] = array( //tipo de documento
                "id" => 1,
                "name" => "Factura de Venta Nacional",
                "code" => "01",
                "cufe_algorithm" => "CUFE-SHA384",
                "prefix" => "fv"
            );


            /**tratamiento del cliente */
            if ($datos->fact_cliente != "" || (empty($datos->fact_cliente) == false)) {

                $cliData = array();
                $cliData = explode('|', $datos->fact_cliente);


                $telefono = empty(trim(str_replace(" ", "", $cliData[7]))) ? '1234567890' : trim(str_replace(" ", "", $cliData[7])); //numero de telefono por defecto
                if (strlen($telefono) < 7 || strlen($telefono) > 10) {
                    $telefono = "1234567890";
                }
                $cliente['cedula'] = trim($cliData[0]);
                $cliente['p_apellido'] = utf8_encode(trim($cliData[1]));
                $cliente['s_apellido'] = utf8_encode(trim($cliData[2]));
                $cliente['p_nombre'] = utf8_encode(trim($cliData[3]));
                $cliente['s_nombre'] = utf8_encode(trim($cliData[4]));
                $cliente['dir'] = utf8_encode(trim($cliData[5]));
                $cliente['barrio'] = utf8_encode(trim($cliData[6]));
                $cliente['telefono'] = $telefono;
                $cliente['email'] = empty(trim($cliData[10])) ? 'CLIENTES@IBG.COM.CO' : trim($cliData[10]); //email by wagner
                $cliente['dv'] = trim($cliData[12]);
                $cliente['tpDoc'] = trim($cliData[11]);
                $cliente['nombres'] = trim($cliData[13]);
                if ($cliente['tpDoc'] == 31) { //si el cliente es juridico
                    $cliente['razon_social'] = trim($cliData[14]);
                } else { //es persona natural
                    $cliente['razon_social'] = "";
                }
            } else {
                continue;
            }

            $factura['customer'] = array(
                "type_document_identification" => $cliente['tpDoc'],  //documento de identificacion
                "identification_number" => $cliente['cedula'],
                "name" => $cliente['nombres'],
                "phone" => $cliente['telefono'],
                "address" => $cliente['dir'],
                "email" => "test@test.com", //FALTA OBTENER EL MAIL
                "merchant_registration" => "No tiene"
            );



            //obteniendo los datos de tax_exclusive_amount
            $tax_exclusive_amount = 0; //base para sacar el IVA de los productos que tienen IVA
            foreach ($datos->fact_detalle as $detalle) {
                if ((int) $detalle->deta_porc_iva != 0 && (int) $detalle->deta_base_prdcto != 0) { //base de productos que tienen IVA y no son regalos
                    $tax_exclusive_amount += ($detalle->deta_cant_prdcto * $detalle->deta_base_prdcto);
                }
            }


            $factura['legal_monetary_totals'] = array(

                "line_extension_amount" => $datos->fact_total,
                "tax_exclusive_amount" => $tax_exclusive_amount,
                "tax_inclusive_amount" => $datos->fact_total,
                "allowance_total_amount" => "0",
                "charge_total_amount" => "0",
                "payable_amount" => $datos->fact_total
            );


            $factura['invoice_lines'] = array();
            $lineas = array();
            $tax_exclusive_amount = 0; //base para sacar el IVA de los productos que tienen IVA

            // print_r($datos);
            // die;
            foreach ($datos->fact_detalle as $detalle) {

                $free_of_charge_indicator = ($detalle->deta_regalo == 'S') ? true : false; //identificar si la fila es un obsequio
                // $total = ($detalle->deta_iva_prdcto) + ($detalle->deta_cant_prdcto * $detalle->deta_base_prdcto);
                $total = $detalle->deta_base_iva_prdcto;

                if (($free_of_charge_indicator == true) && ($detalle->deta_base_prdcto == 0) && ($detalle->deta_iva_prdcto == 0)) { //si hay un regalo sin IVA marranos, vajillas... etc...

                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => $detalle->deta_cant_prdcto,
                        "line_extension_amount" => $total,
                        "free_of_charge_indicator" => $free_of_charge_indicator,
                        "reference_price_id" => 3,
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
                                "tax_amount" => $detalle->deta_iva_prdcto,
                                "taxable_amount" => $detalle->deta_base_prdcto,
                                "percent" => $detalle->deta_porc_iva
                            )

                        ),

                        "description" => $detalle->deta_desprdcto,
                        "code" => $detalle->deta_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => 1, //cambiar esto por la base real del producto
                        "base_quantity" => $detalle->deta_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }

                if (($free_of_charge_indicator == true) && ($detalle->deta_base_prdcto != 0) && ($detalle->deta_iva_prdcto != 0) && ($detalle->deta_porc_iva != 0)) { //Regalos que se le cobran el IVA, televisores, celulares... etc

                    $valor_prdcto_fila = $detalle->deta_base_prdcto + $detalle->deta_iva_prdcto;

                    $ii = ($detalle->deta_base_prdcto * $detalle->deta_cant_prdcto * ($detalle->deta_porc_iva / 100));
                    $valor_x = $this->redondeo($ii);

                    // echo "\ncon regalo sin base -> $valor_x\n";


                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => $detalle->deta_cant_prdcto,
                        "line_extension_amount" => $detalle->deta_iva_prdcto, //El Iva toma el lugar del (total), porque solo se va a cobrar el iva.
                        "free_of_charge_indicator" => false,
                        "allowance_charges" => array(
                            0 => array(
                                "charge_indicator" => false,
                                "allowance_charge_reason" => "Obsequio, al cual solo se le cobra el IVA",
                                "amount" => $detalle->deta_base_prdcto,
                                "base_amount" => $detalle->deta_base_prdcto,
                            )
                        ),
                        "tax_totals" => array(
                            0 => array(
                                "tax_id" => 1,
                                "tax_amount" => $valor_x,
                                // "tax_amount" => $detalle->deta_iva_prdcto,
                                "taxable_amount" => ($detalle->deta_base_prdcto * $detalle->deta_cant_prdcto),
                                "percent" => $detalle->deta_porc_iva
                            )

                        ),

                        "description" => $detalle->deta_desprdcto,
                        "code" => $detalle->deta_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => $valor_prdcto_fila,
                        "base_quantity" => $detalle->deta_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }


                if ($free_of_charge_indicator == false) { //no hay regalo


                    $ii = (($detalle->deta_base_prdcto) * $detalle->deta_cant_prdcto * ($detalle->deta_porc_iva / 100));
                    $valor_x = $this->redondeo($ii);
                    // echo "\nsin regalo-> $valor_x\n";

                    // $iva = $this->redondeo(8979879.51);
                    // dd($iva);

                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => $detalle->deta_cant_prdcto,
                        "line_extension_amount" => $total * $detalle->deta_cant_prdcto,
                        "free_of_charge_indicator" => $free_of_charge_indicator,
                        "allowance_charges" => array(
                            0 => array(
                                "charge_indicator" => false,
                                "allowance_charge_reason" => "Discount",
                                "amount" => 0,
                                "base_amount" => "0"
                            )
                        ),
                        "tax_totals" => array(
                            0 => array(
                                "tax_id" => 1,
                                // "tax_amount" => $detalle->deta_iva_prdcto + $var,
                                "tax_amount" => $valor_x,
                                "taxable_amount" => ($detalle->deta_iva_prdcto == 0) ? 0 : ($detalle->deta_base_prdcto * $detalle->deta_cant_prdcto), //si el producto tiene IVA colocamos la base, de lo contrario (0)
                                "percent" => $detalle->deta_porc_iva
                            )

                        ),

                        "description" => $detalle->deta_desprdcto,
                        "code" => $detalle->deta_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => $total, //precio unitario
                        "base_quantity" => $detalle->deta_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }
            }
            $factura['invoice_lines'] = $lineas;
            // print_r($factura);
            // die;


            $numeroActual++;


            $response = "";
            // $response = $client->request("POST", "invoice/$trackPruebas", [
            //     'body' => json_encode($factura),
            //     'delay' => '5000'
            // ]);
            $response = $client->requestAsync("POST", "invoice/$trackPruebas", [
                'body' => json_encode($factura),
                // 'delay' => '5000'
            ]);
            $response = $response->wait();

            // print_r($response->getBody()->getContents());
            // die;

            $resultado = $response->getBody()->getContents();
            $this->log($resultado);
            $trackId = "";
            $numero =   $factura['prefix'] . $factura['number'];
            $numero2 =   "{$factura['prefix']}-{$factura['number']}";

            if ($response->getStatusCode() == 200) { //si el servidor respondio... verificamos el estado del mensaje

                $resp = array();
                $resp = json_decode($resultado, true);
                // print_r($resp);
                print_r($resp['message']);
                echo "\n**********************************************************";
                print_r($resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']);

                $ruta_xml = $this->guardarComprimido($datos->compania->numnit, $datos->fact_cadv_numsuc, $resp['ZipBase64Bytes'], $numero);

                if (array_key_exists('XmlParamsResponseTrackId', $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList'])) {

                    $mensajeError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['ProcessedMessage'];
                    $codigoError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['SenderCode'];

                    FactRepository::insertTablaLogCadv(
                        $numero,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $numero2,
                        '',
                        $datos->fact_cadv_fecha_imp,
                        $datos->fact_cadv_hora_imp,
                        $datos->fact_cadv_numsuc,
                        $datos->fact_total,
                        $datos->fact_cadv_numdoc,
                        $cliente['cedula'],
                        $datos->fact_coddpto,
                        $datos->fact_codmuni,
                        'CADV',
                        '', //TRACKID nulo
                        '2',
                        $ruta_xml,
                        "$numero.zip"
                    );
                    continue;
                }
                if (is_array($resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']) == false) { //respuesta esperada y correcta
                    $t_id = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']; // trackID

                    FactRepository::insertTablaLogCadv(
                        $numero,
                        "{$resp['message']}",
                        $numero2,
                        '',
                        $datos->fact_cadv_fecha_imp,
                        $datos->fact_cadv_hora_imp,
                        $datos->fact_cadv_numsuc,
                        $datos->fact_total,
                        $datos->fact_cadv_numdoc,
                        $cliente['cedula'],
                        $datos->fact_coddpto,
                        $datos->fact_codmuni,
                        'CADV',
                        $t_id,
                        '1',
                        $ruta_xml,
                        "$numero.zip"
                    );

                    continue;
                } else {

                    FactRepository::insertTablaLogCadv(
                        $numero,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $numero2,
                        '',
                        $datos->fact_cadv_fecha_imp,
                        $datos->fact_cadv_hora_imp,
                        $datos->fact_cadv_numsuc,
                        $datos->fact_total,
                        $datos->fact_cadv_numdoc,
                        $cliente['cedula'],
                        $datos->fact_coddpto,
                        $datos->fact_codmuni,
                        'CADV',
                        '',
                        '2',
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
        // return ($factura);
        // die;



        // print_r(json_encode($factura));die;


        // $response = "";
        // $response = $client->request("POST", "invoice/$trackPruebas", ['body' => json_encode($factura)]);

        // print_r($response->getBody()->getContents());
        // die;


        // $this->log($response->getBody()->getContents());
        // $resp = json_decode($response->getBody()->getContents(), true);


        // return $resp;
        // return $resp['ResponseDian']['Envelope']['Body'];
    }

    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 27/09/2019
     @Descripcion: Metodo que crea un log en la carpeta PUBLIC
     @return:  
     */
    public function log($texto)
    {
        $fecha = Carbon::now();
        $fecha->format('d/m/Y');
        $nombreArchivo = "log_factura_eletronica_{$fecha->format('d')}_{$fecha->format('m')}_{$fecha->format('Y')}";
        $file = fopen("../public/log/{$nombreArchivo}.txt", "a");
        fwrite($file, "******************************************" . PHP_EOL);
        fwrite($file, "Inicio -> {$fecha->format('d/m/Y h:i:s A')}" . PHP_EOL);
        fwrite($file, $texto . PHP_EOL);
        fwrite($file, "Fin -> {$fecha->format('d/m/Y h:i:s A')}" . PHP_EOL);
        fclose($file);
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

        $rutaFinalXML_compri = "XML_COMPRIMIDOS/$nitEmpr2/$sucursal/$anio/$mes/$dia/";

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


    /*
     @autor: Jhonatan W. ocampo
     @Fecha: 30/10/2019
     @Descripcion: Metodo encargado de redondear
     @return:  numero sin decimales
     */
    public function redondeo($var)
    {
        // echo " valor-> $var ";
        $numero_fra = explode('.', $var);
        if (count($numero_fra) > 1) { //si hay decimales en el numero
            if (strlen($numero_fra[1]) >= 2) {
                if (substr($numero_fra[1], 0, 2) > 50) { //si el numero cuenta con mas de 2 decimales, solo se cogen 2 y se verifican si esos 2 son mas de 50
                    return $numero_fra[0] + 1; //se redondea al numero siguiente
                }
                return $numero_fra[0]; //se deja redondea al numero de abajo
            } else {
                if ($numero_fra[1] > 5) { // solo tiene un decimal, y se verifica si es mayor a 5
                    return $numero_fra[0] + 1; // se redonde por encima
                }
                if ($numero_fra[1] == 5) { //si el unico decimal que hay es 5, se procede hacer la revision del numero anterior
                    $numero_precede = substr($numero_fra[0], -1);
                    if ($numero_precede % 2 == 0) {
                        return $numero_fra[0];
                    } else {
                        return $numero_fra[0] + 1;
                    }
                }

                return $numero_fra[0]; //se redondea por debajo porque el unico numero decimal es menor a 5
            }
        } else { //no hay decimales
            return $var;
        }
    }
}
