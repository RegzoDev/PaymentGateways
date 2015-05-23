<?php
use core\GatewayFactory;

require_once('core/GatewayFactory.php');
require_once('core/Config.php');

$gatewayFactory = new GatewayFactory('cobrebem');
$cobrebemGateway = $gatewayFactory->getGatewayObject();