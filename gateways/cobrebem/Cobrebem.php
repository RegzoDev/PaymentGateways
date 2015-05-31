<?php

use core\interfaces\GatewayInterface;
use core\Config;
use core\BaseGateway;

class Cobrebem extends BaseGateway implements  GatewayInterface{

    protected $testMode;
    protected $configName = 'cobrebem';
    protected $testHost = 'https://teste.aprovafacil.com/';
    protected $mainHost = 'https://aprovafacil.com/';

    public function setPaymentCredentials(array $credentialsArray = array()) {
        var_dump($this->apiClient);
    }


} 