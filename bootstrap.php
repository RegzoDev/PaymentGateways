<?php
use core\GatewayFactory;

require_once('core/GatewayFactory.php');
require_once('core/Config.php');
require_once('core/interfaces/GatewayInterface.php');

$parameters = [
    'NumeroDocumento' => md5(time() . '_rezgo'),
    'ValorDocumento' => 0.99,
    'QuantidadeParcelas' => 1,
    'NumeroCartao' => '4222222222222', // string
    'MesValidade' => '10', // card expiration month
    'AnoValidade' => '2017', // card expiration year
    'CodigoSeguranca' => '123', // cvv/cvv2
];

$gatewayFactory = new GatewayFactory('cobrebem');
$cobrebemGateway = $gatewayFactory->getGatewayObject(['testMode' => true]);
$cobrebemGateway->approvalRequest($parameters);
