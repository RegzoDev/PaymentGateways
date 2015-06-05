<?php

use core\interfaces\GatewayInterface;
use core\BaseGateway;

class Cobrebem extends BaseGateway implements  GatewayInterface{

    protected $configName = 'cobrebem';

    public function setPaymentCredentials(array $credentialsArray = array()) {
        var_dump($this->apiClient);
    }


} 