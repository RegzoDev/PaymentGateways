<?php

namespace core;

class GatewayFactory {

    private $gatewayName;
    private $config;
    private $gatewaysPath;

    public function __construct($gatewayName = "") {
        if($gatewayName === "") {
            throw new \Exception('Gateway name must be declared');
        }

        $this->gatewayName = $gatewayName;
        $this->config = new Config();
        $this->gatewaysPath = $this->config->get('gatewaysPath');
    }

    public function getGatewayObject() {
        print 'opana';
    }
}