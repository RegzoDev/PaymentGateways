<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

use core\GatewayFactory;

require_once 'bootstrap.php';

$gatewayFactory = new GatewayFactory('securepay');
$cobrebemGateway = $gatewayFactory->getGatewayObject(['testMode' => true]);
//
/*
$x = $cobrebemGateway->charge([
    'currency' => 'USD',
    'order_id' => 'test'.rand(1001,9000),
    'payment_number' => '4444333322221111',
    'payment_exp_month' => '09',
    'payment_exp_year' => '2015',
    'payment_cvv' => '111',
    'amount' => '20.00',
]);
*/
/*
$cobrebemGateway->refund([
    'currency' => 'USD',
    'order_id' => 'test6828',
    'amount' => '2.00',
    'transaction_id' => '561793',
]);
*/

//$cobrebemGateway->authorizeFinish();
/*
$cobrebemGateway->authorize([
    'currency' => 'USD',
    'order_id' => 'test'.rand(1001,9000),
    'payment_number' => '4444333322221111',
    'payment_exp_month' => '09',
    'payment_exp_year' => '2015',
    'payment_cvv' => '111',
    'amount' => '20.00',
]);
*/
/*
$cobrebemGateway->authorizeFinish([
    'currency' => 'USD',
    'order_id' => 'test6828',
    'amount' => '2.00',
    'transaction_id' => '561793',
]);
*/
/*
$cobrebemGateway->void([
    'currency' => 'USD',
    'order_id' => 'test1851',
    'amount' => '2.00',
    'transaction_id' => '561781',
]);

*/