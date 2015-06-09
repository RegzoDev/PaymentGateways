<?php

use core\interfaces\GatewayInterface;
use core\BaseGateway;

class Cobrebem extends BaseGateway implements  GatewayInterface{

    protected $configName = 'cobrebem';

    public function setPaymentCredentials(array $credentialsArray = array()) {
        var_dump($this->apiClient);
    }

    public function approvalRequest() {

    }

    public function captureRequest() {

    }

    public function cancelTransaction() {

    }

    public function refundCallback() {

    }

    public function refundResult() {

    }


} 