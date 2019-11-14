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

        $nota = array();
        /**datos del certificado */
        $nota['certificate_name'] = "8900016003.p12";
        $nota['certificate_pass'] = "7pC9u9bCEV";
        $nota['sw_identifier'] = "f4dfb118-4e37-4d28-a1aa-922230cb2057";
        $nota['sw_pin'] = "14082";
        $nota['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
        $nota['identification_number'] = "890001600";

        /*datos de la nota */
        $nota['prefix'] = "";
        $nota['number'] = "71";
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

            "number" => "SETP990001968",
            "uuid" => "3e3564eb26b56a7b954793b78b5e0ec2fdb6df30df81e918c82635b41bd53ac3ab80638db6df3044a102f1b639fff55d",
            "issue_date" => "2019-08-01"

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

        $numeroActual = 192;

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


        foreach ($notas as $nota_d) {
            // print_r($nota_d);
            // die;

            $nota = array();

            /**datos del certificado */
            $nota['certificate_name'] = "8900016003.p12";
            $nota['certificate_pass'] = "7pC9u9bCEV";
            $nota['sw_identifier'] = "f4dfb118-4e37-4d28-a1aa-922230cb2057";
            $nota['sw_pin'] = "14082"; //cambiar esto por el dato real
            $nota['url_ws'] = "https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc";
            $nota['identification_number'] = $nota_d->compania->numnit;


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

            if ($nota_d->nd_datos_cliente != "" || (empty($nota_d->nd_datos_cliente) == false)) {

                $cliData = array();
                $cliData = explode('|', $nota_d->nd_datos_cliente);


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

                if ($this->is_valid_email($cliente['email']) == false) { //verificar email
                    $cliente['email'] = 'CLIENTES@IBG.COM.CO';
                }

                // $cliente['email'] = 'clientes@ibg.com.co'; //email by wagner
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


            $nota['customer'] = array(
                "type_document_identification" => $cliente['tpDoc'],
                "identification_number" => $cliente['cedula'],
                "name" => empty($cliente['razon_social']) ? $cliente['nombres'] : $cliente['razon_social'],
                "phone" => $cliente['telefono'],
                "address" => $cliente['dir'],
                "email" => $cliente['email'], //HAY QUE TRAER EL MAIL
                "merchant_registration" => "No tiene"
            );


            //definicion de los impuestos de una NOTA IVA,RETEIVA,RETEFUENTE


            // die(" - {$nota_d->nd_vlrtotal_nota}");

            //obteniendo los datos de tax_exclusive_amount
            $tax_exclusive_amount = 0; //base para sacar el IVA de los productos que tienen IVA
            foreach ($nota_d->nota_detalle as $detalle) {
                if ((int) $detalle->nota_porc_iva != 0 && (int) $detalle->nota_valor_prdcto != 0) { //base de productos que tienen IVA y no son regalos
                    $tax_exclusive_amount += round(($detalle->nota_valor_prdcto) / (($detalle->nota_porc_iva / 100) + 1));
                }
                if ((int) $detalle->nota_porc_iva != 0 && (int) $detalle->nota_valor_prdcto == 0) { //base de productos que son regalos y se les cobra el IVA
                    $tax_exclusive_amount += round($detalle->nota_iva_prdcto / ($detalle->nota_porc_iva / 100));
                }
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

            $nota['debit_note_lines'] = array();
            $lineas = array();
            $tax_exclusive_amount = 0; //base para sacar el IVA de los productos que tienen IVA

            foreach ($nota_d->nota_detalle as $detalle) {


                $free_of_charge_indicator = ($detalle->nota_obsequio == 'S') ? true : false; //identificar si la fila es un obsequio
                // $total = ($detalle->deta_iva_prdcto) + ($detalle->deta_cant_prdcto * $detalle->deta_base_prdcto);
                $total = $detalle->nota_valor_prdcto;

                $base = $detalle->nota_valor_prdcto;

                if ($detalle->nota_porc_iva <> 0 && $detalle->nota_valor_prdcto <> 0) {
                    $base = round(($detalle->nota_valor_prdcto) / (($detalle->nota_porc_iva / 100) + 1));
                }

                if ($detalle->nota_porc_iva <> 0 && $detalle->nota_valor_prdcto == 0) { //base para los regalos
                    $base = round($detalle->nota_iva_prdcto / ($detalle->nota_porc_iva / 100));
                }

                if (($free_of_charge_indicator == true) && ($detalle->nota_valor_prdcto == 0) && ($detalle->nota_iva_prdcto == 0)) { //si hay un regalo sin IVA marranos, vajillas... etc...


                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => 1, //cambiar a valor real
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
                                "tax_amount" => $detalle->nota_iva_prdcto,
                                "taxable_amount" => $base,
                                "percent" => $detalle->nota_porc_iva
                            )

                        ),

                        "description" => $detalle->nota_desprdcto,
                        "code" => $detalle->nota_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => 1, //cambiar esto por la base real del producto
                        "base_quantity" => $detalle->nota_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }

                if (($free_of_charge_indicator == true) && ($detalle->nota_valor_prdcto == 0) && ($detalle->nota_iva_prdcto != 0) && ($detalle->nota_porc_iva != 0)) { //Regalos que se le cobran el IVA, televisores, celulares... etc

                    $valor_prdcto_fila = $base + $detalle->nota_iva_prdcto;

                    // $ii = ($detalle->deta_base_prdcto * $detalle->deta_cant_prdcto * ($detalle->deta_porc_iva / 100));
                    // $valor_x = $this->redondeo($ii);

                    // echo "\ncon regalo sin base -> $valor_x\n";


                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => $detalle->nota_cant_prdcto,
                        "line_extension_amount" => $detalle->nota_iva_prdcto, //El Iva toma el lugar del (total), porque solo se va a cobrar el iva.
                        "free_of_charge_indicator" => false,
                        "allowance_charges" => array(
                            0 => array(
                                "charge_indicator" => false,
                                "allowance_charge_reason" => "Obsequio, al cual solo se le cobra el IVA",
                                "amount" => $base,
                                "base_amount" => $base,
                            )
                        ),
                        "tax_totals" => array(
                            0 => array(
                                "tax_id" => 1,
                                "tax_amount" => $detalle->nota_iva_prdcto,
                                "taxable_amount" => $base,
                                "percent" => $detalle->nota_porc_iva
                            )

                        ),

                        "description" => $detalle->nota_desprdcto,
                        "code" => $detalle->nota_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => $valor_prdcto_fila,
                        "base_quantity" => $detalle->nota_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }


                if ($free_of_charge_indicator == false) { //no hay regalo

                    if ($detalle->nota_porc_iva == 0) { //no tiene iba
                        $base = 0;
                    }


                    // $ii = (($detalle->deta_base_prdcto) * $detalle->deta_cant_prdcto * ($detalle->deta_porc_iva / 100));
                    // $valor_x = $this->redondeo($ii);
                    // echo "\nsin regalo-> $valor_x\n";

                    // $iva = $this->redondeo(8979879.51);
                    // dd($iva);

                    array_push($lineas, array(
                        "unit_measure_id" => 70,
                        "invoiced_quantity" => $detalle->nota_cant_prdcto,
                        "line_extension_amount" => $detalle->nota_valor_prdcto,
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
                                "tax_amount" => $detalle->nota_iva_prdcto,
                                "taxable_amount" => $base,
                                "percent" => $detalle->nota_porc_iva
                            )

                        ),

                        "description" => $detalle->nota_desprdcto,
                        "code" => $detalle->nota_codprdcto,
                        "type_item_identification_id" => 3,
                        "price_amount" => $detalle->nota_valor_prdcto,
                        "base_quantity" => $detalle->nota_cant_prdcto,

                        "unit_measure" => array(
                            "code" => "94"
                        )

                    ));
                }
            }

            $nota['debit_note_lines'] = $lineas;
            // print_r($nota_d->compania);
            // die;



            $response = "";
            $response = $client->requestAsync("POST", "debit-note/$trackPruebas", ['body' => json_encode($nota)]);

            $response = $response->wait();

            $resultado = $response->getBody()->getContents();

            $nombre_log = "log_nota_debito_";
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
                $ruta_xml = $this->guardarComprimido($nota_d->compania->numnit, $nota_d->fact_cadv_numsuc, $resp['ZipBase64Bytes'], $numero);

                if (array_key_exists('XmlParamsResponseTrackId', $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList'])) {
                    $mensajeError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['ProcessedMessage'];
                    $codigoError = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ErrorMessageList']['XmlParamsResponseTrackId']['SenderCode'];

                    NotaDebitoRepository::insertTablaLogNd(
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_numdoc,
                        $numero,
                        $nota_d->nd_canc_nuoren,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_cufe,
                        $nota_d->nd_canc_fecela,
                        $nota_d->nd_hora_nota,
                        $nota_d->nd_sucur,
                        $nota_d->nd_canc_vlrnot,
                        $nota_d->nd_canc_nitcli,
                        $nota_d->nd_coddpto,
                        $nota_d->nd_codmuni,
                        '', //TRACKID nulo
                        "2",
                        $ruta_xml,
                        "$numero.zip"
                    );
                    continue;
                }

                if (is_array($resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']) == false) { //respuesta esperada y correcta
                    $t_id = $resp['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse']['SendTestSetAsyncResult']['ZipKey']; // trackID

                    NotaDebitoRepository::insertTablaLogNd(
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_numdoc,
                        $numero,
                        $nota_d->nd_canc_nuoren,
                        "{$resp['message']}",
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_cufe,
                        $nota_d->nd_canc_fecela,
                        $nota_d->nd_hora_nota,
                        $nota_d->nd_sucur,
                        $nota_d->nd_canc_vlrnot,
                        $nota_d->nd_canc_nitcli,
                        $nota_d->nd_coddpto,
                        $nota_d->nd_codmuni,
                        $t_id,
                        "1",
                        $ruta_xml,
                        "$numero.zip"
                    );

                    continue;
                } else {

                    NotaDebitoRepository::insertTablaLogNd(
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_numdoc,
                        $numero,
                        $nota_d->nd_canc_nuoren,
                        "{$resp['message']} - {$mensajeError} - {$codigoError}",
                        $nota_d->nd_cadv_clase,
                        $nota_d->nd_cadv_cufe,
                        $nota_d->nd_canc_fecela,
                        $nota_d->nd_hora_nota,
                        $nota_d->nd_sucur,
                        $nota_d->nd_canc_vlrnot,
                        $nota_d->nd_canc_nitcli,
                        $nota_d->nd_coddpto,
                        $nota_d->nd_codmuni,
                        '',
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
     @Fecha: 06/11/2019
     @Descripcion: Metodo encargado de validar si el email
     es sintacticamente correcto
     @return:  
     */
    function is_valid_email($str)
    {
        return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
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

        $rutaFinalXML_compri = "XML_NOTAS_DEBITO/$nitEmpr2/$sucursal/$anio/$mes/$dia/";

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
