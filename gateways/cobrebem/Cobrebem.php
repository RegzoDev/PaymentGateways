<?php

use core\interfaces\GatewayInterface;
use core\BaseGateway;

class Cobrebem extends BaseGateway implements  GatewayInterface{

    protected $configName = 'cobrebem';

    const APPROVAL_REQUEST_NAME = 'approvalRequest';

    public function setPaymentCredentials(array $credentialsArray = array()) {

    }

    public function approvalRequest($parameters) {
        $this->validateRequestParameters($parameters, self::APPROVAL_REQUEST_NAME);
        $url = $this->config->get('user', $this->configName) . '/' . $this->config->get('approvalRequestUrl', $this->configName);
        return $this->sendPostRequest($url, $parameters);
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