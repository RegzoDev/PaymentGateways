<?php

namespace core;

class GatewayFactory {

    private $gatewayName;
    private $config;
    private $gatewaysPath;
    private $gatewayPath;
    private $gatewayExt = '.php';

    public function __construct($gatewayName = "") {
        if($gatewayName === "") {
            throw new \Exception('Gateway name must be declared');
        }

        $this->gatewayName = $gatewayName;
        $this->config = new Config();
        $this->gatewaysPath = $this->config->get('gatewaysPath');
        $this->setGatewayPath();
        $this->loadBaseClasses();
    }

    public function getGatewayObject(array $parameters = []) {

        require_once($this->gatewayPath);
        $className = ucfirst($this->gatewayName);
        $object = new $className($parameters);
        return $object;
    }


    private function setGatewayPath() {
       $this->gatewayPath =  $this->gatewaysPath . DIRECTORY_SEPARATOR . strtolower($this->gatewayName) . DIRECTORY_SEPARATOR . ucfirst($this->gatewayName) . $this->gatewayExt;
    }

    private function loadBaseClasses() {
        require_once('core/BaseGateway.php');
        require_once('core/Validator.php');
        require_once('core/Logger.php');
        require 'vendor/autoload.php';

    }
}