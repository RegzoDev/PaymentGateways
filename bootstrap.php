<?php
use core\GatewayFactory;

require_once('core/GatewayFactory.php');
require_once('core/Config.php');
require_once('core/interfaces/GatewayInterface.php');

$gatewayFactory = new GatewayFactory('cobrebem');
$cobrebemGateway = $gatewayFactory->getGatewayObject(['testMode' => true]);
$cobrebemGateway->setPaymentCredentials();
