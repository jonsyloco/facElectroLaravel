<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/******************************FACTURAS *******************************/
Route::get('fac/resultFact', [
    'uses'    => 'FacturaController@getResultFact',
    'as'    => 'factura.resultFact'
]);


/**envio de facturas */
Route::get('fac/sendInvoice', [
    'uses'    => 'FacturaController@sendInvoice',
    'as'    => 'factura.sendInvoice'
]);

/**envio de facturas final */
Route::get('fac/sendInvoice2', [
    'uses'    => 'FacturaController@sendInvoice2',
    'as'    => 'factura.sendInvoice2'
]);

/*************************recuperar el estado de las facturas*****************/
Route::get('getFac/', [
    'uses'    => 'ResultFacturasController@index',
    'as'    => 'getFac.index'
]);
Route::get('getFac/gfact', [
    'uses'    => 'ResultFacturasController@obtenerResulFactPendientes',
    'as'    => 'getFac.gfact'
]);

/****************NOTAS CREDITO ******************************************/
Route::get('not_cred/index', [
    'uses'    => 'NotaCreditoController@index',
    'as'    => 'notaCredito.index'
]);

Route::get('not_cred/getNotaCredito', [
    'uses'    => 'NotaCreditoController@getNotaCredito',
    'as'    => 'notaCredito.getNotaCredito'
]);

Route::get('not_cred/sendNotaCredito', [
    'uses'    => 'NotaCreditoController@sendNotaCredito',
    'as'    => 'notaCredito.sendNotaCredito'
]);

Route::get('not_cred/sendNotaCredito2', [
    'uses'    => 'NotaCreditoController@sendNotaCredito2',
    'as'    => 'notaCredito.sendNotaCredito2'
]);

/*****************************NOTA DEBITO ****************************/
Route::get('not_deb/index', [
    'uses'    => 'NotaDebitocontroller@index',
    'as'    => 'notaDebito.index'
]);

Route::get('not_deb/getResultNota', [
    'uses'    => 'NotaDebitocontroller@getResultNota',
    'as'    => 'notaDebito.getResultNota'
]);

Route::get('not_deb/sendNotaDebito', [ //envio de notas de prueba
    'uses'    => 'NotaDebitocontroller@sendNotaDebito',
    'as'    => 'notaDebito.sendNotaDebito'
]);

Route::get('not_deb/sendNotaDebito2', [ //envio de notas full
    'uses'    => 'NotaDebitocontroller@sendNotaDebito2',
    'as'    => 'notaDebito.sendNotaDebito2'
]);


/*********** Recupera el estado de las notas debito*********/
Route::get('getNotD/', [
    'uses'    => 'ResultNotaDController@index',
    'as'    => 'getNotD.index'
]);
Route::get('getNotD/gNotaD', [
    'uses'    => 'ResultNotaDController@obtenerResulNotaPendientes',
    'as'    => 'getNotD.gNotaD'
]);

/*********** Recupera el estado de las notas credito*********/
Route::get('getNotC/', [
    'uses'    => 'ResultNotaCrController@index',
    'as'    => 'getNotC.index'
]);
Route::get('getNotC/gNotaC', [
    'uses'    => 'ResultNotaCrController@obtenerResulNotaPendientes',
    'as'    => 'getNotC.gNotaC'
]);





/*********************************ALIAS*******************************/
Route::Resource('fac', 'FacturaController');
Route::Resource('getFac', 'ResultFacturasController'); //recuperar facturas
Route::Resource('getNotD', 'ResultNotaDController'); //recuperar notas debito
Route::Resource('getNotC', 'ResultNotaCrController'); //recuperar notas credito
Route::Resource('not_cred', 'NotaCreditoController');
Route::Resource('not_deb', 'NotaDebitoController');
Route::get('/', 'FacturaController@index');

